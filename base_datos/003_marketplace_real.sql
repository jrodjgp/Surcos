-- Migracion incremental para una base Surcos ya creada.
-- No borra datos existentes: agrega tramos de precio y actualiza procedimientos.

use surcos;

create table if not exists tramos_precio_pool (
    id varchar(64) primary key,
    pool_id varchar(64) not null,
    compradores_minimos int not null,
    precio_unitario decimal(10, 2) not null,
    etiqueta varchar(120) not null,
    creado_en timestamp not null default current_timestamp,
    constraint tramos_pool_fk foreign key (pool_id) references pools(id) on delete cascade,
    constraint tramos_compradores_chk check (compradores_minimos >= 1),
    constraint tramos_precio_chk check (precio_unitario > 0),
    unique key tramos_pool_compradores_unico (pool_id, compradores_minimos)
) engine=InnoDB;

insert into tramos_precio_pool (id, pool_id, compradores_minimos, precio_unitario, etiqueta)
values
    ('tramo-geisha-base', 'grupo-geisha-42', 1, 0.62, 'Precio base del pool'),
    ('tramo-geisha-volumen', 'grupo-geisha-42', 10, 0.58, 'Precio por volumen medio'),
    ('tramo-geisha-meta', 'grupo-geisha-42', 20, 0.52, 'Precio al completar meta'),
    ('tramo-tomates-base', 'grupo-tomates-09', 1, 0.45, 'Precio base del pool'),
    ('tramo-tomates-volumen', 'grupo-tomates-09', 25, 0.40, 'Precio por volumen medio'),
    ('tramo-tomates-meta', 'grupo-tomates-09', 50, 0.36, 'Precio al completar meta'),
    ('tramo-miel-base', 'grupo-miel-cruda', 1, 4.20, 'Precio base del pool'),
    ('tramo-miel-volumen', 'grupo-miel-cruda', 25, 3.95, 'Precio por volumen medio'),
    ('tramo-miel-meta', 'grupo-miel-cruda', 50, 3.70, 'Precio al completar meta'),
    ('tramo-cacao-base', 'grupo-cacao-07', 1, 2.35, 'Precio base del pool'),
    ('tramo-cacao-volumen', 'grupo-cacao-07', 20, 2.15, 'Precio por volumen medio'),
    ('tramo-cacao-meta', 'grupo-cacao-07', 35, 1.98, 'Precio al completar meta'),
    ('tramo-arbequina-base', 'grupo-arbequina-azuero', 1, 9.75, 'Precio base del pool'),
    ('tramo-arbequina-volumen', 'grupo-arbequina-azuero', 12, 8.90, 'Precio por volumen medio'),
    ('tramo-arbequina-meta', 'grupo-arbequina-azuero', 25, 8.20, 'Precio al completar meta')
on duplicate key update
    precio_unitario = values(precio_unitario),
    etiqueta = values(etiqueta);

delimiter //

drop procedure if exists sp_confirmar_compromiso_pool//

create procedure sp_confirmar_compromiso_pool(
    in p_borrador_id varchar(64),
    in p_usuario_id varchar(64),
    in p_metodo_pago_id varchar(64),
    out p_compromiso_id varchar(64),
    out p_referencia varchar(80)
)
begin
    declare v_pool_id varchar(64);
    declare v_producto varchar(180);
    declare v_estado_pool varchar(20);
    declare v_actuales int;
    declare v_objetivo int;
    declare v_fecha_cierre datetime;
    declare v_cantidad decimal(10, 2);
    declare v_precio_vigente decimal(10, 2);
    declare v_monto decimal(10, 2);
    declare v_metodo_activo tinyint default 0;
    declare v_metodo_etiqueta varchar(120);
    declare v_marca varchar(60);
    declare v_ultimos varchar(4);

    declare exit handler for sqlexception
    begin
        rollback;
        resignal;
    end;

    start transaction;

    select c.pool_id, c.producto_snapshot, c.cantidad, p.estado, p.personas_actuales,
           p.personas_objetivo, p.fecha_cierre
      into v_pool_id, v_producto, v_cantidad, v_estado_pool, v_actuales,
           v_objetivo, v_fecha_cierre
      from compromisos c
      join pools p on p.id = c.pool_id
     where c.id = p_borrador_id
       and c.usuario_id = p_usuario_id
       and c.estado_compromiso = 'borrador'
     for update;

    if v_pool_id is null then
        signal sqlstate '45000' set message_text = 'El borrador no existe o ya fue procesado.';
    end if;

    if v_estado_pool <> 'activo' or v_fecha_cierre < now() then
        signal sqlstate '45000' set message_text = 'El pool ya no esta activo.';
    end if;

    if v_actuales >= v_objetivo then
        signal sqlstate '45000' set message_text = 'El pool ya no tiene cupo disponible.';
    end if;

    select activo, etiqueta, marca, ultimos
      into v_metodo_activo, v_metodo_etiqueta, v_marca, v_ultimos
      from metodos_pago
     where id = p_metodo_pago_id
       and usuario_id = p_usuario_id
     limit 1;

    if v_metodo_activo <> 1 then
        signal sqlstate '45000' set message_text = 'Metodo de pago simulado no disponible.';
    end if;

    select coalesce((
        select t.precio_unitario
          from tramos_precio_pool t
         where t.pool_id = v_pool_id
           and t.compradores_minimos <= v_actuales + 1
      order by t.compradores_minimos desc
         limit 1
    ), p.precio_grupal)
      into v_precio_vigente
      from pools p
     where p.id = v_pool_id;

    set v_monto = round(v_cantidad * v_precio_vigente, 2);
    set p_compromiso_id = p_borrador_id;
    set p_referencia = concat('SIM-', upper(substr(replace(uuid(), '-', ''), 1, 12)));

    update compromisos
       set estado_compromiso = 'confirmado',
           estado_grupo = if(v_actuales + 1 >= v_objetivo, 'ganado', 'pendiente'),
           monto = v_monto,
           metodo_pago_id = p_metodo_pago_id,
           metodo_pago_etiqueta = v_metodo_etiqueta,
           fecha = current_date
     where id = p_borrador_id;

    update pools
       set personas_actuales = personas_actuales + 1
     where id = v_pool_id;

    if v_actuales + 1 >= v_objetivo then
        update compromisos
           set estado_grupo = 'ganado'
         where pool_id = v_pool_id
           and estado_compromiso = 'confirmado'
           and estado_grupo = 'pendiente';

        update pools
           set estado = 'cerrado'
         where id = v_pool_id;
    end if;

    insert into intentos_pago (
        id, usuario_id, compromiso_id, metodo_pago_id, referencia, monto,
        marca, ultimos, estado, autorizacion_simulada
    ) values (
        concat('pago-', replace(uuid(), '-', '')),
        p_usuario_id,
        p_borrador_id,
        p_metodo_pago_id,
        p_referencia,
        v_monto,
        v_marca,
        v_ultimos,
        'simulado_autorizado',
        concat('AUT-', upper(substr(replace(uuid(), '-', ''), 1, 10)))
    );

    insert into actividad (id, usuario_id, tipo, texto, fecha)
    values (
        concat('act-', replace(uuid(), '-', '')),
        p_usuario_id,
        'compromiso',
        concat('Compromiso confirmado para ', v_producto, '. Referencia ', p_referencia),
        current_date
    );

    commit;
end//

drop procedure if exists sp_cerrar_pools_vencidos//

create procedure sp_cerrar_pools_vencidos()
begin
    update compromisos c
    join pools p on p.id = c.pool_id
       set c.estado_grupo = 'ganado'
     where p.estado = 'activo'
       and p.fecha_cierre < now()
       and p.personas_actuales >= p.personas_objetivo
       and c.estado_compromiso = 'confirmado'
       and c.estado_grupo = 'pendiente';

    update pools
       set estado = 'cerrado'
     where estado = 'activo'
       and fecha_cierre < now()
       and personas_actuales >= personas_objetivo;

    update compromisos c
    join pools p on p.id = c.pool_id
       set c.estado_grupo = 'fallido'
     where p.estado = 'activo'
       and p.fecha_cierre < now()
       and p.personas_actuales < p.personas_objetivo
       and c.estado_compromiso = 'confirmado'
       and c.estado_grupo = 'pendiente';

    update pools
       set estado = 'fallido'
     where estado = 'activo'
       and fecha_cierre < now()
       and personas_actuales < personas_objetivo;
end//

delimiter ;

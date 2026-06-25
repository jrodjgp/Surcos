-- Esquema principal de Surcos para MySQL/MariaDB.
-- Ejecutar en phpMyAdmin o en la consola mysql de XAMPP.

create database if not exists surcos
  character set utf8mb4
  collate utf8mb4_unicode_ci;

use surcos;

set foreign_key_checks = 0;

drop table if exists actividad;
drop table if exists intentos_pago;
drop table if exists compromisos;
drop table if exists metodos_pago;
drop table if exists tramos_precio_pool;
drop table if exists pools;
drop table if exists eventos_solicitud;
drop table if exists solicitudes_contacto;
drop table if exists productores;
drop table if exists usuarios;
drop table if exists nodos_retiro;
drop table if exists administradores;

set foreign_key_checks = 1;

create table administradores (
    id varchar(64) primary key,
    nombre varchar(120) not null,
    correo varchar(160) not null unique,
    clave_hash varchar(255) not null,
    activo tinyint(1) not null default 1,
    creado_en timestamp not null default current_timestamp,
    actualizado_en timestamp not null default current_timestamp on update current_timestamp
) engine=InnoDB;

create table nodos_retiro (
    id varchar(64) primary key,
    provincia varchar(80) not null,
    nombre varchar(120) not null,
    direccion varchar(255),
    activo tinyint(1) not null default 1,
    creado_en timestamp not null default current_timestamp,
    actualizado_en timestamp not null default current_timestamp on update current_timestamp,
    unique key nodos_provincia_nombre_unico (provincia, nombre)
) engine=InnoDB;

create table usuarios (
    id varchar(64) primary key,
    nombre varchar(140) not null,
    correo varchar(160) not null unique,
    clave_hash varchar(255) not null,
    telefono varchar(40),
    rol enum('comprador', 'productor', 'empresa', 'aliado_logistico') not null default 'comprador',
    estado enum('pendiente', 'activo', 'suspendido') not null default 'pendiente',
    provincia varchar(80),
    nodo_retiro_id varchar(64),
    iniciales varchar(8),
    debe_cambiar_clave tinyint(1) not null default 0,
    creado_en timestamp not null default current_timestamp,
    actualizado_en timestamp not null default current_timestamp on update current_timestamp,
    constraint usuarios_nodo_fk foreign key (nodo_retiro_id) references nodos_retiro(id)
) engine=InnoDB;

create table productores (
    id varchar(64) primary key,
    usuario_id varchar(64),
    nombre varchar(140) not null,
    responsable varchar(140) not null,
    provincia varchar(80) not null,
    zona varchar(120) not null,
    especialidad varchar(140) not null,
    historia text,
    estado enum('pendiente', 'activo', 'suspendido') not null default 'activo',
    creado_en timestamp not null default current_timestamp,
    actualizado_en timestamp not null default current_timestamp on update current_timestamp,
    constraint productores_usuario_fk foreign key (usuario_id) references usuarios(id)
) engine=InnoDB;

create table solicitudes_contacto (
    id varchar(64) primary key,
    nombre varchar(140) not null,
    correo varchar(160) not null,
    telefono varchar(40),
    tipo_usuario enum('comprador', 'productor', 'empresa', 'aliado_logistico') not null,
    asunto varchar(180) not null,
    mensaje text not null,
    acepta_contacto tinyint(1) not null default 0,
    estado enum('nueva', 'en_revision', 'aprobada', 'rechazada') not null default 'nueva',
    notas_admin text,
    usuario_creado_id varchar(64),
    creada_en timestamp not null default current_timestamp,
    actualizada_en timestamp not null default current_timestamp on update current_timestamp,
    constraint solicitudes_usuario_fk foreign key (usuario_creado_id) references usuarios(id)
) engine=InnoDB;

create table eventos_solicitud (
    id varchar(64) primary key,
    solicitud_id varchar(64) not null,
    administrador_id varchar(64),
    tipo varchar(80) not null,
    detalle text,
    creado_en timestamp not null default current_timestamp,
    constraint eventos_solicitud_fk foreign key (solicitud_id) references solicitudes_contacto(id) on delete cascade,
    constraint eventos_admin_fk foreign key (administrador_id) references administradores(id)
) engine=InnoDB;

create table pools (
    id varchar(64) primary key,
    productor_id varchar(64) not null,
    producto varchar(140) not null,
    variedad varchar(140) not null,
    categoria varchar(80) not null,
    origen varchar(160) not null,
    imagen_url varchar(500),
    precio_mercado decimal(10, 2) not null,
    precio_grupal decimal(10, 2) not null,
    unidad varchar(30) not null,
    personas_actuales int not null default 0,
    personas_objetivo int not null,
    cantidad_minima decimal(10, 2) not null,
    fecha_cierre datetime not null,
    fecha_entrega date not null,
    estado enum('activo', 'cerrado', 'fallido') not null default 'activo',
    modelo_entrega varchar(120) not null,
    nodo_retiro_id varchar(64),
    creado_en timestamp not null default current_timestamp,
    actualizado_en timestamp not null default current_timestamp on update current_timestamp,
    constraint pools_productor_fk foreign key (productor_id) references productores(id),
    constraint pools_nodo_fk foreign key (nodo_retiro_id) references nodos_retiro(id),
    constraint pools_personas_chk check (personas_actuales >= 0 and personas_objetivo > 0),
    constraint pools_cantidad_chk check (cantidad_minima > 0)
) engine=InnoDB;

create table tramos_precio_pool (
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

create table metodos_pago (
    id varchar(64) primary key,
    usuario_id varchar(64) not null,
    tipo varchar(60) not null,
    etiqueta varchar(120) not null,
    marca varchar(60) not null,
    ultimos varchar(4) not null,
    principal tinyint(1) not null default 0,
    activo tinyint(1) not null default 1,
    creado_en timestamp not null default current_timestamp,
    actualizado_en timestamp not null default current_timestamp on update current_timestamp,
    constraint metodos_usuario_fk foreign key (usuario_id) references usuarios(id) on delete cascade
) engine=InnoDB;

create table compromisos (
    id varchar(64) primary key,
    pool_id varchar(64) not null,
    usuario_id varchar(64) not null,
    producto_snapshot varchar(180) not null,
    origen_snapshot varchar(180) not null,
    cantidad decimal(10, 2) not null,
    unidad varchar(30) not null,
    monto decimal(10, 2) not null,
    metodo_pago_id varchar(64),
    metodo_pago_etiqueta varchar(120),
    estado_compromiso enum('borrador', 'confirmado', 'cancelado') not null default 'borrador',
    estado_grupo enum('pendiente', 'ganado', 'fallido', 'cancelado') not null default 'pendiente',
    estado_entrega enum('programado', 'transito', 'entregado', 'cancelado') not null default 'programado',
    fecha date not null,
    fecha_cancelacion date,
    creado_en timestamp not null default current_timestamp,
    actualizado_en timestamp not null default current_timestamp on update current_timestamp,
    constraint compromisos_pool_fk foreign key (pool_id) references pools(id),
    constraint compromisos_usuario_fk foreign key (usuario_id) references usuarios(id) on delete cascade,
    constraint compromisos_metodo_fk foreign key (metodo_pago_id) references metodos_pago(id)
) engine=InnoDB;

create table intentos_pago (
    id varchar(64) primary key,
    usuario_id varchar(64) not null,
    compromiso_id varchar(64),
    metodo_pago_id varchar(64),
    referencia varchar(80) not null unique,
    monto decimal(10, 2) not null,
    marca varchar(60) not null,
    ultimos varchar(4) not null,
    estado enum('simulado_autorizado', 'simulado_fallido') not null,
    autorizacion_simulada varchar(80),
    creado_en timestamp not null default current_timestamp,
    constraint pagos_usuario_fk foreign key (usuario_id) references usuarios(id) on delete cascade,
    constraint pagos_compromiso_fk foreign key (compromiso_id) references compromisos(id) on delete set null,
    constraint pagos_metodo_fk foreign key (metodo_pago_id) references metodos_pago(id) on delete set null
) engine=InnoDB;

create table actividad (
    id varchar(64) primary key,
    usuario_id varchar(64),
    tipo varchar(80) not null,
    texto varchar(255) not null,
    fecha date not null,
    creado_en timestamp not null default current_timestamp,
    constraint actividad_usuario_fk foreign key (usuario_id) references usuarios(id) on delete cascade
) engine=InnoDB;

create index solicitudes_contacto_estado_idx on solicitudes_contacto(estado);
create index pools_estado_idx on pools(estado);
create index tramos_pool_idx on tramos_precio_pool(pool_id, compradores_minimos);
create index compromisos_usuario_idx on compromisos(usuario_id, estado_compromiso);
create index actividad_usuario_idx on actividad(usuario_id, fecha);

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

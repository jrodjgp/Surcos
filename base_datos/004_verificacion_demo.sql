-- Verificacion read-only del seed demo de Surcos.
-- Ejecutar despues de 001_esquema.sql, 002_semillas_demo.sql y 003_marketplace_real.sql.

use surcos;

select 'administradores_activos' as chequeo, count(*) as total
  from administradores
 where activo = 1;

select 'usuarios_demo_activos' as chequeo, count(*) as total
  from usuarios
 where correo in ('comprador@surcos.pa', 'productor@surcos.pa', 'empresa@surcos.pa')
   and estado = 'activo';

select 'productores_activos' as chequeo, count(*) as total
  from productores
 where estado = 'activo';

select 'pools_activos_futuros' as chequeo, count(*) as total
  from pools
 where estado = 'activo'
   and fecha_cierre > now();

select 'tramos_precio' as chequeo, count(*) as total
  from tramos_precio_pool;

select estado as estado_solicitud, count(*) as total
  from solicitudes_contacto
 group by estado
 order by estado;

select 'metodos_pago_comprador' as chequeo, count(*) as total
  from metodos_pago
 where usuario_id = 'usr-comprador-demo'
   and activo = 1;

select 'metodos_pago_empresa' as chequeo, count(*) as total
  from metodos_pago
 where usuario_id = 'usr-empresa-demo'
   and activo = 1;

select 'pools_sin_productor_activo' as chequeo, count(*) as total
  from pools p
  left join productores pr on pr.id = p.productor_id and pr.estado = 'activo'
 where pr.id is null;

select 'pools_sin_tramos' as chequeo, count(*) as total
  from pools p
  left join tramos_precio_pool t on t.pool_id = p.id
 where t.id is null;

select 'imagenes_externas' as chequeo, count(*) as total
  from pools
 where imagen_url like 'http://%'
    or imagen_url like 'https://%';

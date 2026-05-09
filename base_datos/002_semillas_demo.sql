-- Datos demo de Surcos migrados desde js/datos/datosIniciales.js.
-- Ejecutar despues de 001_esquema.sql.

insert into administradores (id, nombre, correo, clave_hash, activo)
values
    ('admin-surcos', 'Admin Surcos', 'admin@surcos.pa', '$2y$10$i7UsxPR3VcSPXebxm4FU7ucM1y0aDxWAYsxnRHxbdfZeHGqREbWuS', true)
on conflict (id) do update set
    nombre = excluded.nombre,
    correo = excluded.correo,
    clave_hash = excluded.clave_hash,
    activo = excluded.activo;

insert into nodos_retiro (id, provincia, nombre, direccion)
values
    ('nodo-david-centro', 'Chiriqui', 'Nodo David Centro', null),
    ('nodo-chitre-norte', 'Herrera', 'Nodo Chitre Norte', null),
    ('nodo-las-tablas', 'Los Santos', 'Nodo Las Tablas', null),
    ('nodo-penonome', 'Cocle', 'Nodo Penonome', null),
    ('nodo-pty-terminal-oeste', 'Panama', 'PTY Terminal Oeste', 'Via Espana, frente al Rey'),
    ('nodo-colon-puerto', 'Colon', 'Nodo Colon Puerto', null),
    ('nodo-meteti', 'Darien', 'Nodo Meteti', null),
    ('nodo-mercado-central', 'Panama', 'Mercado Central', null)
on conflict (id) do update set
    provincia = excluded.provincia,
    nombre = excluded.nombre,
    direccion = excluded.direccion;

insert into usuarios (id, nombre, correo, clave_hash, telefono, rol, estado, provincia, nodo_retiro_id, iniciales)
values
    ('usr-nodo-panama', 'Juan Juanes', 'nodo@surcos.pa', '$2y$10$Mm87hFjnzUG75vZMibFWXeMcRvt1Enc7PbmI5SAFdenQefyXAWTcq', '+507 6000-0000', 'comprador', 'activo', 'Panama', 'nodo-pty-terminal-oeste', 'JJ')
on conflict (id) do update set
    nombre = excluded.nombre,
    correo = excluded.correo,
    telefono = excluded.telefono,
    rol = excluded.rol,
    estado = excluded.estado,
    provincia = excluded.provincia,
    nodo_retiro_id = excluded.nodo_retiro_id,
    iniciales = excluded.iniciales;

insert into productores (id, nombre, responsable, provincia, zona, especialidad, historia)
values
    ('prod-heredia', 'Finca Heredia', 'Don Sebastian Heredia', 'Chiriqui', 'Boquete', 'Cafe Geisha', 'Productor de micro-lotes de altura con proceso honey y perfiles florales.'),
    ('prod-oasis', 'Finca Oasis', 'Ana Rodriguez', 'Chiriqui', 'Tierras Altas', 'Tomates de Herencia', 'Cultivo de hortalizas de temporada en suelos volcanicos y clima frio.'),
    ('prod-bosque', 'Apiario Bosque Silvestre', 'Luis Medina', 'Cocle', 'El Valle', 'Miel Cruda', 'Apiario artesanal con cosechas pequenas y trazabilidad por floracion.'),
    ('prod-darien', 'Cooperativa Darien Cacao', 'Marta Quintero', 'Darien', 'Meteti', 'Cacao Crudo', 'Cooperativa familiar enfocada en cacao fermentado de origen unico.'),
    ('prod-azuero', 'Finca Azuero Verde', 'Carlos Batista', 'Herrera', 'Chitre', 'Aceite Arbequina', 'Produccion limitada de aceite prensado en frio para pedidos grupales.')
on conflict (id) do update set
    nombre = excluded.nombre,
    responsable = excluded.responsable,
    provincia = excluded.provincia,
    zona = excluded.zona,
    especialidad = excluded.especialidad,
    historia = excluded.historia;

insert into pools (
    id, productor_id, producto, variedad, categoria, origen, imagen_url,
    precio_mercado, precio_grupal, unidad, personas_actuales, personas_objetivo,
    cantidad_minima, fecha_cierre, fecha_entrega, estado, modelo_entrega, nodo_retiro_id
)
values
    ('grupo-geisha-42', 'prod-heredia', 'Cafe Geisha', 'Micro-lote #42', 'cafe', 'Tierras Altas, Chiriqui', 'https://images.unsplash.com/photo-1447933601403-0c6688de566e?w=800&q=80&fit=crop', 1.10, 0.62, 'lb', 17, 20, 2, '2026-05-18T23:59:00-05:00', '2026-05-30', 'activo', 'Retiro en Nodo', 'nodo-pty-terminal-oeste'),
    ('grupo-geisha-04-historico', 'prod-heredia', 'Cafe Geisha', 'Honey #04', 'cafe', 'Boquete, Chiriqui', 'https://images.unsplash.com/photo-1447933601403-0c6688de566e?w=800&q=80&fit=crop', 1.10, 0.62, 'lb', 20, 20, 2, '2026-04-10T23:59:00-05:00', '2026-04-12', 'cerrado', 'Retiro en Nodo', 'nodo-pty-terminal-oeste'),
    ('grupo-tomates-09', 'prod-oasis', 'Tomates de Herencia', 'Lote 09', 'hortalizas', 'Boquete, Chiriqui', 'https://images.unsplash.com/photo-1592924357228-91a4daadcfea?w=800&q=80&fit=crop', 0.85, 0.45, 'lb', 21, 50, 5, '2026-05-15T23:59:00-05:00', '2026-05-30', 'activo', 'Retiro en Nodo', 'nodo-mercado-central'),
    ('grupo-miel-cruda', 'prod-bosque', 'Miel Silvestre Artesanal', 'Cruda', 'miel', 'El Valle, Cocle', 'https://images.unsplash.com/photo-1558642452-9d2a7deb7f62?w=800&q=80&fit=crop', 8.50, 4.20, 'lb', 49, 50, 1, '2026-05-20T23:59:00-05:00', '2026-06-03', 'activo', 'Envio a Domicilio', 'nodo-penonome'),
    ('grupo-miel-01-historico', 'prod-bosque', 'Miel Silvestre Artesanal', 'Cruda', 'miel', 'El Valle, Cocle', 'https://images.unsplash.com/photo-1558642452-9d2a7deb7f62?w=800&q=80&fit=crop', 8.50, 4.20, 'lb', 50, 50, 1, '2026-04-02T23:59:00-05:00', '2026-04-04', 'cerrado', 'Envio a Domicilio', 'nodo-penonome'),
    ('grupo-cacao-07', 'prod-darien', 'Cacao Crudo', 'Lote 7', 'cacao', 'Meteti, Darien', 'https://images.unsplash.com/photo-1606313564200-e75d5e30476c?w=800&q=80&fit=crop', 3.90, 2.35, 'lb', 12, 35, 3, '2026-05-22T23:59:00-05:00', '2026-06-05', 'activo', 'Retiro en Nodo', 'nodo-pty-terminal-oeste'),
    ('grupo-arbequina-azuero', 'prod-azuero', 'Aceite Arbequina', 'Prensado en frio', 'aceite', 'Chitre, Herrera', 'https://images.unsplash.com/photo-1474979266404-7eaacbcd87c5?w=800&q=80&fit=crop', 14.50, 9.75, 'botella', 8, 25, 1, '2026-05-28T23:59:00-05:00', '2026-06-10', 'activo', 'Lote Empresarial', 'nodo-chitre-norte')
on conflict (id) do update set
    productor_id = excluded.productor_id,
    producto = excluded.producto,
    variedad = excluded.variedad,
    categoria = excluded.categoria,
    origen = excluded.origen,
    imagen_url = excluded.imagen_url,
    precio_mercado = excluded.precio_mercado,
    precio_grupal = excluded.precio_grupal,
    unidad = excluded.unidad,
    personas_actuales = excluded.personas_actuales,
    personas_objetivo = excluded.personas_objetivo,
    cantidad_minima = excluded.cantidad_minima,
    fecha_cierre = excluded.fecha_cierre,
    fecha_entrega = excluded.fecha_entrega,
    estado = excluded.estado,
    modelo_entrega = excluded.modelo_entrega,
    nodo_retiro_id = excluded.nodo_retiro_id;

insert into metodos_pago (id, usuario_id, tipo, etiqueta, ultimos, principal)
values
    ('pago-visa-4821', 'usr-nodo-panama', 'VISA', 'Tarjeta principal', '4821', true),
    ('pago-mastercard-0934', 'usr-nodo-panama', 'MASTERCARD', 'Tarjeta respaldo', '0934', false),
    ('pago-yappy', 'usr-nodo-panama', 'YAPPY PA', 'Yappy Panama', 'Yappy', false)
on conflict (id) do update set
    usuario_id = excluded.usuario_id,
    tipo = excluded.tipo,
    etiqueta = excluded.etiqueta,
    ultimos = excluded.ultimos,
    principal = excluded.principal;

insert into compromisos (
    id, pool_id, usuario_id, producto_snapshot, origen_snapshot,
    cantidad, unidad, monto, metodo_pago_id, metodo_pago_etiqueta,
    estado_compromiso, estado_grupo, estado_entrega, fecha
)
values
    ('ord-geisha-04', 'grupo-geisha-04-historico', 'usr-nodo-panama', 'Geisha Honey #04', 'Boquete', 2, 'lb', 218.00, 'pago-visa-4821', 'VISA 4821', 'confirmado', 'ganado', 'entregado', '2026-04-12'),
    ('ord-miel-01', 'grupo-miel-01-historico', 'usr-nodo-panama', 'Miel Silvestre Artesanal', 'El Valle, Cocle', 1, 'lb', 96.50, 'pago-visa-4821', 'VISA 4821', 'confirmado', 'ganado', 'entregado', '2026-04-04'),
    ('ord-cacao-07', 'grupo-cacao-07', 'usr-nodo-panama', 'Cacao Crudo Lote 7', 'Darien', 3, 'lb', 133.00, 'pago-visa-4821', 'VISA 4821', 'confirmado', 'pendiente', 'transito', '2026-03-29')
on conflict (id) do update set
    pool_id = excluded.pool_id,
    usuario_id = excluded.usuario_id,
    producto_snapshot = excluded.producto_snapshot,
    origen_snapshot = excluded.origen_snapshot,
    cantidad = excluded.cantidad,
    unidad = excluded.unidad,
    monto = excluded.monto,
    metodo_pago_id = excluded.metodo_pago_id,
    metodo_pago_etiqueta = excluded.metodo_pago_etiqueta,
    estado_compromiso = excluded.estado_compromiso,
    estado_grupo = excluded.estado_grupo,
    estado_entrega = excluded.estado_entrega,
    fecha = excluded.fecha;

insert into intentos_pago (id, usuario_id, compromiso_id, metodo_pago_id, referencia, monto, marca, ultimos, estado, autorizacion_simulada)
values
    ('pago-intento-geisha-04', 'usr-nodo-panama', 'ord-geisha-04', 'pago-visa-4821', 'SIM-GEISHA-04', 218.00, 'VISA', '4821', 'simulado_autorizado', 'AUTH-GEISHA-04'),
    ('pago-intento-miel-01', 'usr-nodo-panama', 'ord-miel-01', 'pago-visa-4821', 'SIM-MIEL-01', 96.50, 'VISA', '4821', 'simulado_autorizado', 'AUTH-MIEL-01'),
    ('pago-intento-cacao-07', 'usr-nodo-panama', 'ord-cacao-07', 'pago-visa-4821', 'SIM-CACAO-07', 133.00, 'VISA', '4821', 'simulado_autorizado', 'AUTH-CACAO-07')
on conflict (referencia) do update set
    usuario_id = excluded.usuario_id,
    compromiso_id = excluded.compromiso_id,
    metodo_pago_id = excluded.metodo_pago_id,
    monto = excluded.monto,
    marca = excluded.marca,
    ultimos = excluded.ultimos,
    estado = excluded.estado,
    autorizacion_simulada = excluded.autorizacion_simulada;

insert into actividad (id, usuario_id, tipo, texto, fecha)
values
    ('act-geisha', 'usr-nodo-panama', 'grupo', 'Comprometido al grupo Cafe Geisha Micro-lote #42', '2026-04-12'),
    ('act-perfil', 'usr-nodo-panama', 'perfil', 'Preferencias de notificacion actualizadas', '2026-04-10')
on conflict (id) do update set
    usuario_id = excluded.usuario_id,
    tipo = excluded.tipo,
    texto = excluded.texto,
    fecha = excluded.fecha;

insert into solicitudes_contacto (id, nombre, correo, telefono, tipo_usuario, asunto, mensaje, acepta_contacto, estado, notas_admin)
values
    ('sol-demo-productor-oasis', 'Ana Rodriguez', 'ana.oasis@example.com', '+507 6111-2026', 'productor', 'Quiero publicar una cosecha', 'Tengo un lote nuevo de tomates de herencia y quiero afiliar mi finca a Surcos.', true, 'nueva', null),
    ('sol-demo-empresa', 'Compras Corporativas PTY', 'compras@example.com', '+507 6222-2026', 'empresa', 'Compra grupal empresarial', 'Buscamos lotes recurrentes para abastecer una oficina en Ciudad de Panama.', true, 'en_revision', 'Llamar despues de revisar capacidad de entrega.')
on conflict (id) do update set
    nombre = excluded.nombre,
    correo = excluded.correo,
    telefono = excluded.telefono,
    tipo_usuario = excluded.tipo_usuario,
    asunto = excluded.asunto,
    mensaje = excluded.mensaje,
    acepta_contacto = excluded.acepta_contacto,
    estado = excluded.estado,
    notas_admin = excluded.notas_admin;


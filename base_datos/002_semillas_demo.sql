-- Datos demo de Surcos para MySQL/MariaDB.
-- Credenciales demo:
-- Admin: admin@surcos.pa / Admin123!
-- Comprador: comprador@surcos.pa / Surcos123!
-- Productor: productor@surcos.pa / Surcos123!

use surcos;

insert into administradores (id, nombre, correo, clave_hash, activo)
values
    ('admin-surcos', 'Admin Surcos', 'admin@surcos.pa', '$2y$10$P6fH5gkjKg2jM2WYAL58B.8txaxaKNFZJ7DlvisKWMygDqKJwbP/.', 1)
on duplicate key update
    nombre = values(nombre),
    correo = values(correo),
    clave_hash = values(clave_hash),
    activo = values(activo);

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
on duplicate key update
    provincia = values(provincia),
    nombre = values(nombre),
    direccion = values(direccion);

insert into usuarios (id, nombre, correo, clave_hash, telefono, rol, estado, provincia, nodo_retiro_id, iniciales)
values
    ('usr-comprador-demo', 'Juan Juanes', 'comprador@surcos.pa', '$2y$10$9TGhRYmE3ZdiNIzP3JIgMu2wX143QFuSKAXV4BZu79htj21ptyFjC', '+507 6000-0000', 'comprador', 'activo', 'Panama', 'nodo-pty-terminal-oeste', 'JJ'),
    ('usr-productor-demo', 'Ana Rodriguez', 'productor@surcos.pa', '$2y$10$9TGhRYmE3ZdiNIzP3JIgMu2wX143QFuSKAXV4BZu79htj21ptyFjC', '+507 6777-4410', 'productor', 'activo', 'Chiriqui', 'nodo-david-centro', 'AR')
on duplicate key update
    nombre = values(nombre),
    correo = values(correo),
    telefono = values(telefono),
    rol = values(rol),
    estado = values(estado),
    provincia = values(provincia),
    nodo_retiro_id = values(nodo_retiro_id),
    iniciales = values(iniciales);

insert into productores (id, usuario_id, nombre, responsable, provincia, zona, especialidad, historia, estado)
values
    ('prod-heredia', null, 'Finca Heredia', 'Don Sebastian Heredia', 'Chiriqui', 'Boquete', 'Cafe Geisha', 'Productor de micro-lotes de altura con proceso honey y perfiles florales.', 'activo'),
    ('prod-oasis', 'usr-productor-demo', 'Finca Oasis', 'Ana Rodriguez', 'Chiriqui', 'Tierras Altas', 'Tomates de Herencia', 'Cultivo de hortalizas de temporada en suelos volcanicos y clima frio.', 'activo'),
    ('prod-bosque', null, 'Apiario Bosque Silvestre', 'Luis Medina', 'Cocle', 'El Valle', 'Miel Cruda', 'Apiario artesanal con cosechas pequenas y trazabilidad por floracion.', 'activo'),
    ('prod-darien', null, 'Cooperativa Darien Cacao', 'Marta Quintero', 'Darien', 'Meteti', 'Cacao Crudo', 'Cooperativa familiar enfocada en cacao fermentado de origen unico.', 'activo'),
    ('prod-azuero', null, 'Finca Azuero Verde', 'Carlos Batista', 'Herrera', 'Chitre', 'Aceite Arbequina', 'Produccion limitada de aceite prensado en frio para pedidos grupales.', 'activo')
on duplicate key update
    usuario_id = values(usuario_id),
    nombre = values(nombre),
    responsable = values(responsable),
    provincia = values(provincia),
    zona = values(zona),
    especialidad = values(especialidad),
    historia = values(historia),
    estado = values(estado);

insert into pools (
    id, productor_id, producto, variedad, categoria, origen, imagen_url,
    precio_mercado, precio_grupal, unidad, personas_actuales, personas_objetivo,
    cantidad_minima, fecha_cierre, fecha_entrega, estado, modelo_entrega, nodo_retiro_id
)
values
    ('grupo-geisha-42', 'prod-heredia', 'Cafe Geisha', 'Micro-lote #42', 'cafe', 'Tierras Altas, Chiriqui', null, 1.10, 0.62, 'lb', 17, 20, 2, '2026-07-03 23:59:00', '2026-07-12', 'activo', 'Retiro en Nodo', 'nodo-pty-terminal-oeste'),
    ('grupo-tomates-09', 'prod-oasis', 'Tomates de Herencia', 'Lote 09', 'hortalizas', 'Boquete, Chiriqui', null, 0.85, 0.45, 'lb', 21, 50, 5, '2026-07-05 23:59:00', '2026-07-16', 'activo', 'Retiro en Nodo', 'nodo-mercado-central'),
    ('grupo-miel-cruda', 'prod-bosque', 'Miel Silvestre Artesanal', 'Cruda', 'miel', 'El Valle, Cocle', null, 8.50, 4.20, 'lb', 49, 50, 1, '2026-07-08 23:59:00', '2026-07-20', 'activo', 'Envio a Domicilio', 'nodo-penonome'),
    ('grupo-cacao-07', 'prod-darien', 'Cacao Crudo', 'Lote 7', 'cacao', 'Meteti, Darien', null, 3.90, 2.35, 'lb', 12, 35, 3, '2026-07-10 23:59:00', '2026-07-24', 'activo', 'Retiro en Nodo', 'nodo-pty-terminal-oeste'),
    ('grupo-arbequina-azuero', 'prod-azuero', 'Aceite Arbequina', 'Prensado en frio', 'aceite', 'Chitre, Herrera', null, 14.50, 9.75, 'botella', 8, 25, 1, '2026-07-15 23:59:00', '2026-07-30', 'activo', 'Lote Empresarial', 'nodo-chitre-norte')
on duplicate key update
    producto = values(producto),
    variedad = values(variedad),
    origen = values(origen),
    imagen_url = values(imagen_url),
    precio_mercado = values(precio_mercado),
    precio_grupal = values(precio_grupal),
    personas_actuales = values(personas_actuales),
    personas_objetivo = values(personas_objetivo),
    fecha_cierre = values(fecha_cierre),
    fecha_entrega = values(fecha_entrega),
    estado = values(estado);

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

insert into metodos_pago (id, usuario_id, tipo, etiqueta, marca, ultimos, principal, activo)
values
    ('pago-demo-visa', 'usr-comprador-demo', 'tarjeta_simulada', 'Visa simulada terminada en 4242', 'Visa', '4242', 1, 1),
    ('pago-demo-clave', 'usr-comprador-demo', 'tarjeta_simulada', 'Clave simulada terminada en 0188', 'Clave', '0188', 0, 1)
on duplicate key update
    etiqueta = values(etiqueta),
    marca = values(marca),
    ultimos = values(ultimos),
    principal = values(principal),
    activo = values(activo);

insert into solicitudes_contacto (id, nombre, correo, telefono, tipo_usuario, asunto, mensaje, acepta_contacto, estado, notas_admin)
values
    ('sol-demo-productor', 'Maria Batista', 'maria.finca@example.com', '+507 6777-2211', 'productor', 'Quiero afiliar una finca', 'Tengo produccion de hortalizas en Tierras Altas y deseo publicar lotes por temporada.', 1, 'nueva', null),
    ('sol-demo-empresa', 'Compras Hotel Central', 'compras.hotel@example.com', '+507 2222-1000', 'empresa', 'Compra recurrente para cocina', 'Buscamos entrar a pools de cafe, miel y hortalizas para abastecimiento mensual.', 1, 'en_revision', 'Validar volumen estimado antes de aprobar.')
on duplicate key update
    nombre = values(nombre),
    correo = values(correo),
    telefono = values(telefono),
    tipo_usuario = values(tipo_usuario),
    asunto = values(asunto),
    mensaje = values(mensaje),
    acepta_contacto = values(acepta_contacto),
    estado = values(estado),
    notas_admin = values(notas_admin);

insert into actividad (id, usuario_id, tipo, texto, fecha)
values
    ('act-demo-1', 'usr-comprador-demo', 'pool', 'Cafe Geisha alcanzo 85% del cupo objetivo.', '2026-06-24'),
    ('act-demo-2', 'usr-comprador-demo', 'pago', 'Metodo de pago simulado disponible para confirmar bandeja.', '2026-06-24')
on duplicate key update
    texto = values(texto),
    fecha = values(fecha);

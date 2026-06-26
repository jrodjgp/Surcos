# Base de datos Surcos

Ruta oficial del Proyecto 2: MySQL/MariaDB en XAMPP.

Ejecuta estos archivos en phpMyAdmin o en la consola `mysql`, en este orden:

1. `001_esquema.sql`
2. `002_semillas_demo.sql`

Si la base ya existia antes de agregar tramos de precio y cierre automatico, ejecuta ademas:

3. `003_marketplace_real.sql`

Para revisar que la demo quedo completa, ejecuta despues:

4. `004_verificacion_demo.sql` (solo consultas, no modifica datos)

Credenciales demo:

- Admin: `admin@surcos.pa` / `Admin123!`
- Comprador: `comprador@surcos.pa` / `Surcos123!`
- Productor: `productor@surcos.pa` / `Surcos123!`
- Empresa: `empresa@surcos.pa` / `Surcos123!`

Notas:

- Los pagos son simulados. Solo se guardan marca, referencia, monto y ultimos caracteres ficticios.
- No se guarda numero completo de tarjeta ni CVV.
- Las solicitudes demo cubren `nueva`, `en_revision`, `aprobada` y `rechazada`.
- `eventos_solicitud` guarda la bitacora de revision administrativa de una solicitud. No representa ventas ni historial de pools.
- `usr-productor-demo` esta vinculado a `prod-oasis` para probar publicacion de pools sin crear tablas extra.
- `usr-empresa-demo` esta vinculado a una solicitud aprobada para mostrar una cuenta generada desde admin.
- `tramos_precio_pool` modela la logica de compra grupal: a mayor cantidad de compradores, menor precio unitario vigente.
- `sp_confirmar_compromiso_pool` recalcula el precio por tramo antes de autorizar el pago simulado. Si el pool llega al objetivo, marca todos los compromisos confirmados como `ganado` y cierra el pool.
- `sp_cerrar_pools_vencidos` actualiza pools vencidos a `cerrado` o `fallido`; se ejecuta desde una accion POST del admin.
- Las imagenes de cosecha se resuelven desde assets locales en PHP; el seed no depende de URLs externas.
- Las fechas de cierre y entrega se calculan con `now()` y `curdate()` para que la demo no venza por fechas fijas.

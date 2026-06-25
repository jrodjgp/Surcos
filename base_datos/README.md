# Base de datos Surcos

Ruta oficial del Proyecto 2: MySQL/MariaDB en XAMPP.

Ejecuta estos archivos en phpMyAdmin o en la consola `mysql`, en este orden:

1. `001_esquema.sql`
2. `002_semillas_demo.sql`

Credenciales demo:

- Admin: `admin@surcos.pa` / `Admin123!`
- Comprador: `comprador@surcos.pa` / `Surcos123!`

Notas:

- Los pagos son simulados. Solo se guardan marca, referencia, monto y ultimos caracteres ficticios.
- No se guarda numero completo de tarjeta ni CVV.
- Las solicitudes demo existen para probar el panel admin.
- `eventos_solicitud` guarda la bitacora de revision administrativa de una solicitud. No representa ventas ni historial de pools.

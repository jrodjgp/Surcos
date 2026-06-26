# Surcos - Proyecto 2

Surcos es un marketplace agricola panameno que reduce la distancia entre productores y compradores de volumen. Compradores se unen a pools de compra colectiva y productores publican lotes de cosecha con precio, cupo, origen y cierre claros. Esta version esta orientada a la rubrica de Proyecto 2: PHP, MySQL/MariaDB, MVC, sesiones, procedimiento almacenado y web service.

## Stack

- PHP 8 con PDO
- MySQL/MariaDB en XAMPP
- HTML5 y CSS3
- MVC liviano sin framework externo
- Sesiones PHP y token CSRF

## Credenciales Demo

- Admin: `admin@surcos.pa` / `Admin123!`
- Comprador: `comprador@surcos.pa` / `Surcos123!`
- Productor: `productor@surcos.pa` / `Surcos123!`
- Empresa: `empresa@surcos.pa` / `Surcos123!`

## Instalacion Local

1. En XAMPP, enciende Apache y MySQL.
2. Copia `.env.ejemplo` como `.env`.
3. Confirma estas variables para XAMPP:

```env
MYSQL_HOST=127.0.0.1
MYSQL_PORT=3306
MYSQL_DATABASE=surcos
MYSQL_USER=root
MYSQL_PASSWORD=
MOSTRAR_DETALLE_SALUD=false
```

4. Importa los SQL en phpMyAdmin, en este orden:

```text
base_datos/001_esquema.sql
base_datos/002_semillas_demo.sql
```

Si ya tenias una base `surcos` creada antes de la capa marketplace real, ejecuta tambien:

```text
base_datos/003_marketplace_real.sql
```

Para comprobar que el seed demo quedo completo:

```text
base_datos/004_verificacion_demo.sql
```

5. Configura Apache o el virtual host para que el document root apunte a `publico/`. Abre el sitio desde:

```text
http://surcos.local/
```

Tambien puedes usar el servidor embebido:

```bash
C:\xampp\php\php.exe -S 127.0.0.1:8000 -t publico
```

## Rutas Principales

- `/` Marketplace de pools y registro de cosecha.
- `/nosotros.php` Historia, problema y modelo comercial de Surcos.
- `/historias_productor.php` Historias de productores conectadas a sus pools activos.
- `/historias_productor.php?productor=prod-heredia` Historia de un productor especifico.
- `/contacto.php` Solicitud de afiliacion guardada en MySQL.
- `/ingreso.php` Login de comprador/productor.
- `/activar_cuenta.php` Cambio de clave temporal para cuentas pendientes.
- `/pool.php?id=grupo-geisha-42` Detalle de pool.
- `/bandeja.php` Bandeja de Pools con compromisos en borrador.
- `/historial_pools.php` Actividad e historial comercial del comprador.
- `/productor/` Panel del productor activo: pools, tramos, cierres y monto simulado.
- `/admin/` Login de administrador.
- `/admin/solicitudes.php` Gestion de solicitudes.
- `/admin/pools.php` Revision de pools publicados.
- `/api/pools.php` Web service JSON de pools activos.
- `/api/pool.php?id=grupo-geisha-42` Web service JSON de un pool con tramos.
- `/api/productores.php` Web service JSON de productores activos.
- `/salud.php` Diagnostico seguro de PHP, sesiones y MySQL. Solo muestra detalle interno si `MOSTRAR_DETALLE_SALUD=true`.

## Prueba Rapida Del Flujo

1. Abre `/` y entra a un pool activo desde `Mercado de Pools`.
2. Entra con `comprador@surcos.pa` / `Surcos123!`.
3. Agrega la cantidad minima del pool a la Bandeja.
4. Confirma la Bandeja con un metodo de pago simulado.
5. Revisa `/historial_pools.php`.
6. Entra a `/admin/` con `admin@surcos.pa` / `Admin123!`.
7. Revisa solicitudes, aprueba una solicitud nueva y confirma que se muestra una clave temporal una sola vez.
8. Revisa `/api/pools.php`, `/api/pool.php?id=grupo-geisha-42` y `/salud.php`.

## QA De Rubrica

La evidencia tecnica de Proyecto 2 esta documentada en `QA.md`: checklist de rubrica, comandos ejecutados, rutas probadas, procedimientos almacenados, web services, sesiones, CSRF y flujo manual para el profesor.

## Demo Final

El recorrido recomendado para revisar el proyecto sin explicacion adicional esta en `DEMO.md`: preparacion de XAMPP, credenciales, flujo comprador, flujo productor, flujo admin, web services, salud y reglas de congelacion de features.

## Entrega Congelada

Antes de hacer commit o push final, usa `ENTREGA.md` como punto de control: lista de archivos finales, comandos, rutas que deben probarse, cambios permitidos durante freeze y mensaje de commit sugerido.

## MVC

La aplicacion PHP vive en `aplicacion/`:

- `Controladores`: reciben solicitudes HTTP, validan CSRF y coordinan modelos/vistas.
- `Modelos`: encapsulan consultas PDO y llamadas al procedimiento almacenado.
- `Vistas`: plantillas HTML5 reutilizables.
- `Soporte`: sesiones, CSRF, conexion PDO, helpers y autenticacion.

`publico/` es el document root y contiene los entrypoints publicos.

Los archivos HTML/JS del primer prototipo quedaron archivados en `referencia_legacy/`. No forman parte de la demo oficial de Proyecto 2.

## Base de Datos

El esquema usa varias tablas relacionadas:

- `administradores`
- `usuarios`
- `productores`
- `solicitudes_contacto`
- `eventos_solicitud`
- `pools`
- `tramos_precio_pool`
- `compromisos`
- `metodos_pago`
- `intentos_pago`
- `actividad`

Los procedimientos almacenados principales son:

- `sp_confirmar_compromiso_pool`: valida que el pool este activo, que exista cupo y que el metodo de pago simulado pertenezca al usuario. Recalcula el precio vigente por tramo antes de confirmar el compromiso y crear el pago simulado.
- `sp_cerrar_pools_vencidos`: cierra pools vencidos como `cerrado` o `fallido` segun hayan alcanzado el objetivo. En admin se ejecuta con accion POST, no al cargar la pagina.

El seed incluye un productor activo vinculado a `productor@surcos.pa` para probar la publicacion real simple de cosechas. Las solicitudes aprobadas desde admin crean una cuenta pendiente; si el tipo es productor, tambien crean un perfil productor pendiente vinculado.
Tambien incluye una cuenta empresa activa, solicitudes en estados `nueva`, `en_revision`, `aprobada` y `rechazada`, eventos de bitacora admin, cinco productores activos y cinco pools con fechas relativas para que no expiren por una fecha fija.

## Seguridad

- Formularios POST con token CSRF.
- Contrasenas con `password_hash` y `password_verify`.
- Sesiones PHP con cookie `httponly` y `samesite=Lax`.
- Consultas PDO preparadas.
- Salida HTML escapada con `htmlspecialchars`.
- Pagos solo simulados: se guardan marca, ultimos 4 ficticios, monto, estado y referencia simulada. No se guarda numero completo ni CVV.
- Las claves temporales de aprobacion se muestran una sola vez en mensaje de sesion, no se guardan en notas ni bitacora, y obligan a cambiar clave antes de usar bandeja o publicar pools.

## Checklist Rubrica

- HTML5: si.
- CSS3: si.
- PHP: si, visible en rutas, controladores y vistas.
- MySQL/MariaDB: si.
- Mas de una tabla: si.
- MVC: si, estructura `Controladores`, `Modelos`, `Vistas`.
- Procedimiento almacenado: si, `sp_confirmar_compromiso_pool` y `sp_cerrar_pools_vencidos`.
- Web service: si, `/api/pools.php`, `/api/pool.php` y `/api/productores.php`.
- Cookies/sesiones: si, login comprador y admin.
- Seguridad en formularios: si, CSRF, validacion, PDO y escape.

## Notas

Supabase/PostgreSQL quedo fuera de la ruta principal para evitar conflicto con la rubrica literal que pide MySQL. Puede retomarse despues como version de portafolio o despliegue alternativo.

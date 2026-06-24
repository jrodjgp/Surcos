# Surcos - Proyecto 2

Surcos es un marketplace agricola panameno donde compradores se unen a pools de compra colectiva y productores publican lotes de cosecha. Esta version esta orientada a la rubrica de Proyecto 2: PHP, MySQL/MariaDB, MVC, sesiones, procedimiento almacenado y web service.

## Stack

- PHP 8 con PDO
- MySQL/MariaDB en XAMPP
- HTML5 y CSS3
- MVC liviano sin framework externo
- Sesiones PHP y token CSRF

## Credenciales Demo

- Admin: `admin@surcos.pa` / `Admin123!`
- Comprador: `comprador@surcos.pa` / `Surcos123!`

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
```

4. Importa los SQL en phpMyAdmin, en este orden:

```text
base_datos/001_esquema.sql
base_datos/002_semillas_demo.sql
```

5. Abre el sitio desde el virtual host:

```text
http://surcos.local/
```

Tambien puedes usar el servidor embebido:

```bash
C:\xampp\php\php.exe -S 127.0.0.1:8000 -t publico
```

## Rutas Principales

- `/` Marketplace de pools y registro de cosecha.
- `/contacto.php` Solicitud de afiliacion guardada en MySQL.
- `/ingreso.php` Login de comprador/productor.
- `/pool.php?id=grupo-geisha-42` Detalle de pool.
- `/bandeja.php` Bandeja de Pools con compromisos en borrador.
- `/admin/` Login de administrador.
- `/admin/solicitudes.php` Gestion de solicitudes.
- `/admin/pools.php` Revision de pools publicados.
- `/api/pools.php` Web service JSON de pools activos.
- `/salud.php` Diagnostico de PHP, sesiones y MySQL.

## MVC

La aplicacion PHP vive en `aplicacion/`:

- `Controladores`: reciben solicitudes HTTP, validan CSRF y coordinan modelos/vistas.
- `Modelos`: encapsulan consultas PDO y llamadas al procedimiento almacenado.
- `Vistas`: plantillas HTML5 reutilizables.
- `Soporte`: sesiones, CSRF, conexion PDO, helpers y autenticacion.

`publico/` es el document root y contiene los entrypoints publicos.

## Base de Datos

El esquema usa varias tablas relacionadas:

- `administradores`
- `usuarios`
- `productores`
- `solicitudes_contacto`
- `eventos_solicitud`
- `pools`
- `compromisos`
- `metodos_pago`
- `intentos_pago`
- `actividad`

El procedimiento almacenado principal es `sp_confirmar_compromiso_pool`. Valida que el pool este activo, que exista cupo y que el metodo de pago simulado pertenezca al usuario antes de confirmar un compromiso.

## Seguridad

- Formularios POST con token CSRF.
- Contrasenas con `password_hash` y `password_verify`.
- Sesiones PHP con cookie `httponly` y `samesite=Lax`.
- Consultas PDO preparadas.
- Salida HTML escapada con `htmlspecialchars`.
- Pagos solo simulados: se guardan marca, ultimos 4 ficticios, monto, estado y referencia simulada. No se guarda numero completo ni CVV.

## Checklist Rubrica

- HTML5: si.
- CSS3: si.
- PHP: si, visible en rutas, controladores y vistas.
- MySQL/MariaDB: si.
- Mas de una tabla: si.
- MVC: si, estructura `Controladores`, `Modelos`, `Vistas`.
- Procedimiento almacenado: si, `sp_confirmar_compromiso_pool`.
- Web service: si, `/api/pools.php`.
- Cookies/sesiones: si, login comprador y admin.
- Seguridad en formularios: si, CSRF, validacion, PDO y escape.

## Notas

Supabase/PostgreSQL quedo fuera de la ruta principal para evitar conflicto con la rubrica literal que pide MySQL. Puede retomarse despues como version de portafolio o despliegue alternativo.

# Demo Final Surcos

Este documento es el recorrido recomendado para presentar o revisar Surcos sin explicacion adicional. La app oficial corre desde `publico/` con PHP, MySQL/MariaDB, HTML5, CSS3 y MVC liviano.

## Antes de Probar

1. Encender Apache y MySQL en XAMPP.
2. Confirmar que el VirtualHost o document root apunta a:

```text
C:\Users\jose\Documents\VS\Surcos\publico
```

3. Confirmar `.env` con MySQL local:

```env
MYSQL_HOST=127.0.0.1
MYSQL_PORT=3306
MYSQL_DATABASE=surcos
MYSQL_USER=root
MYSQL_PASSWORD=
MOSTRAR_DETALLE_SALUD=false
```

4. Si se necesita reconstruir la base, importar en orden:

```text
base_datos/001_esquema.sql
base_datos/002_semillas_demo.sql
base_datos/003_marketplace_real.sql
```

5. Verificar seed:

```powershell
& 'C:\xampp\mysql\bin\mysql.exe' -u root surcos --batch --raw --execute="source C:/Users/jose/Documents/VS/Surcos/base_datos/004_verificacion_demo.sql"
```

La verificacion debe mostrar 12 tablas en el esquema, 5 productores activos, 5 pools activos futuros, 15 tramos de precio, solicitudes en los 4 estados y 0 imagenes externas.

## Credenciales Demo

- Admin: `admin@surcos.pa` / `Admin123!`
- Comprador: `comprador@surcos.pa` / `Surcos123!`
- Productor: `productor@surcos.pa` / `Surcos123!`
- Empresa: `empresa@surcos.pa` / `Surcos123!`

## Recorrido Principal

### 1. Landing

Abrir:

```text
http://surcos.local/
```

Debe entenderse en menos de 5 segundos:

- Surcos conecta compradores con pools de compra agricola.
- Productores publican lotes de cosecha.
- El precio baja por tramos cuando aumenta el volumen comprometido.

Verificar visualmente:

- `Mercado de Pools` muestra pools activos.
- Cada pool muestra precio, progreso, cierre, origen y CTA.
- La navegacion apunta a `Nosotros`, `Historias`, `Contacto`, `Ingresar` y/o `Bandeja`.

### 2. Comprador y Bandeja

1. Entrar a `/ingreso.php`.
2. Login con `comprador@surcos.pa` / `Surcos123!`.
3. Abrir `/pool.php?id=grupo-geisha-42`.
4. Agregar la cantidad minima o sugerida a la Bandeja.
5. Abrir `/bandeja.php`.
6. Confirmar la Bandeja con metodo de pago simulado.
7. Revisar `/historial_pools.php`.

Resultado esperado:

- El compromiso pasa de borrador a confirmado.
- Se crea autorizacion de pago simulada.
- El historial muestra actividad comercial del comprador.
- No se solicita ni guarda tarjeta real ni CVV.

### 3. Productor

1. Login con `productor@surcos.pa` / `Surcos123!`.
2. Abrir `/productor/`.
3. Revisar pools publicados, tramos, cierres y monto simulado.
4. Probar el formulario de publicacion con datos validos.

Resultado esperado:

- El formulario solo aparece para usuario activo con rol productor y productor vinculado.
- El pool queda ligado al productor autenticado.
- La publicacion usa datos reales de nodos, categoria, unidad, cierre y entrega.

### 4. Historias y Producto

Abrir:

```text
/historias_productor.php
/historias_productor.php?productor=prod-heredia
/nosotros.php
```

Resultado esperado:

- Historias muestra productores conectados a pools activos.
- Cada historia enlaza a pools reales.
- Nosotros explica el problema de intermediarios, el modelo de pools y el enfoque panameno de Surcos.

### 5. Admin

1. Abrir `/admin/`.
2. Login con `admin@surcos.pa` / `Admin123!`.
3. Ir a `/admin/solicitudes.php`.
4. Revisar filtros y estados `nueva`, `en_revision`, `aprobada`, `rechazada`.
5. Abrir una solicitud nueva.
6. Aprobar o rechazar.

Resultado esperado:

- La bitacora se llama `Bitacora de solicitud` o `Historial de revision`, no eventos comerciales.
- Al aprobar, se crea cuenta pendiente.
- Si la solicitud es de productor, se crea perfil productor pendiente vinculado.
- La clave temporal se muestra solo una vez en flash y no queda guardada en notas ni bitacora.

### 6. Web Services

Abrir:

```text
/api/pools.php
/api/pool.php?id=grupo-geisha-42
/api/productores.php
```

Resultado esperado:

- Responden `application/json`.
- Muestran datos reales desde MySQL.
- No requieren sesion para lectura publica.

### 7. Salud

Abrir:

```text
/salud.php
```

Resultado esperado:

- Muestra estado de PHP, sesiones y MySQL.
- No expone host, usuario, DSN ni secretos mientras `MOSTRAR_DETALLE_SALUD=false`.

## Evidencia Rapida de Rubrica

- HTML5: cabecera, vistas semanticas y formularios.
- CSS3: estilos en `publico/recursos/css/`.
- PHP: controladores, modelos, vistas y entrypoints publicos.
- MySQL/MariaDB: `base_datos/001_esquema.sql`.
- MVC: `aplicacion/Controladores`, `aplicacion/Modelos`, `aplicacion/Vistas`.
- Procedimientos almacenados: `sp_confirmar_compromiso_pool`, `sp_cerrar_pools_vencidos`.
- WS REST/JSON: `/api/pools.php`, `/api/pool.php`, `/api/productores.php`.
- Sesiones/cookies: login comprador, productor, empresa y admin.
- Seguridad: CSRF, PDO preparado, `password_hash`, `password_verify`, escape HTML y pagos simulados.

Mas detalle en `QA.md`.

## Congelacion de Features

Desde esta fase, no se deben agregar features nuevas salvo bug critico. Cambios permitidos:

- Correcciones de errores.
- Ajustes de texto que aclaren la demo.
- Ajustes visuales menores que no cambien flujo.
- Fixes de seguridad.
- Correcciones de datos demo.

Cambios no recomendados antes de entregar:

- Redisenar la landing completa otra vez.
- Cambiar stack o base de datos.
- Agregar frameworks.
- Cambiar nombres de rutas.
- Rehacer modelos o tablas sin necesidad.
- Agregar pagos reales, correo real o integraciones externas.

## Comandos Finales

Sintaxis PHP:

```powershell
$errores = @(); foreach ($archivo in (rg --files -g '*.php')) { $salida = & 'C:\xampp\php\php.exe' -l $archivo 2>&1; if ($LASTEXITCODE -ne 0) { $errores += $salida } }; if ($errores.Count -gt 0) { $errores; exit 1 } else { 'php -l OK: todos los PHP sin errores de sintaxis' }
```

Whitespace:

```powershell
git diff --check
```

Verificacion demo:

```powershell
& 'C:\xampp\mysql\bin\mysql.exe' -u root surcos --batch --raw --execute="source C:/Users/jose/Documents/VS/Surcos/base_datos/004_verificacion_demo.sql"
```

## Decision Tecnica

Surcos usa REST/JSON para web services. No usa SOAP porque la rubrica pide implementar al menos un web service, pero no exige SOAP especificamente.

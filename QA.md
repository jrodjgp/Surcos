# QA de Rubrica Surcos

Este archivo documenta la evidencia tecnica para Proyecto 2. La app oficial es la version PHP en `publico/`, no los archivos legacy archivados como referencia.

## Resultado

Estado actual: cumple los puntos principales de la rubrica con PHP, HTML5, CSS3, MySQL/MariaDB, MVC, sesiones, CSRF, procedimientos almacenados y web services REST en JSON.

## Checklist de Rubrica

- Sitio web con HTML5: si. La cabecera define `<!DOCTYPE html>`, `lang="es"` y `viewport`; las vistas usan `main`, `section`, `article`, `nav`, `form`, `table`, `header` y `footer`.
- CSS3: si. Los estilos estan en `publico/recursos/css/` con variables, grid, flex, media queries, estados `:focus-visible` y layout responsive.
- PHP: si. Las rutas publicas y admin ejecutan controladores PHP y vistas PHP.
- MySQL/MariaDB: si. El proyecto usa XAMPP/MariaDB con PDO.
- Mas de una tabla: si. El esquema tiene 12 tablas.
- MVC: si. La estructura principal es `aplicacion/Controladores`, `aplicacion/Modelos`, `aplicacion/Vistas` y `aplicacion/Soporte`, con `publico/` como document root.
- Procedimiento almacenado: si. `sp_confirmar_compromiso_pool` y `sp_cerrar_pools_vencidos`.
- Web service: si. `/api/pools.php`, `/api/pool.php?id=grupo-geisha-42` y `/api/productores.php` devuelven JSON.
- Cookies/sesiones: si. Login comprador, productor, empresa y admin usan sesiones PHP.
- Seguridad de formularios: si. Formularios POST usan CSRF, PDO preparado, validacion backend, escape de salida y hashes de contrasena.

## Evidencia de Base de Datos

Comando de conteo:

```powershell
& 'C:\xampp\mysql\bin\mysql.exe' -u root surcos --batch --raw --execute="select count(*) as tablas from information_schema.tables where table_schema = database()"
```

Resultado:

```text
tablas
12
```

Tablas principales:

- `administradores`
- `nodos_retiro`
- `usuarios`
- `productores`
- `solicitudes_contacto`
- `eventos_solicitud`
- `pools`
- `tramos_precio_pool`
- `metodos_pago`
- `compromisos`
- `intentos_pago`
- `actividad`

Procedimientos almacenados verificados:

```text
sp_cerrar_pools_vencidos
sp_confirmar_compromiso_pool
```

Script de verificacion demo:

```powershell
& 'C:\xampp\mysql\bin\mysql.exe' -u root surcos --batch --raw --execute="source C:/Users/jose/Documents/VS/Surcos/base_datos/004_verificacion_demo.sql"
```

Resultado relevante:

```text
administradores_activos: 1
usuarios_demo_activos: 3
productores_activos: 5
pools_activos_futuros: 5
tramos_precio: 15
solicitudes nueva/en_revision/aprobada/rechazada: 1 cada una
metodos_pago_comprador: 2
metodos_pago_empresa: 1
pools_sin_productor_activo: 0
pools_sin_tramos: 0
imagenes_externas: 0
```

## Evidencia HTTP

Rutas verificadas con respuesta `200`:

- `/`
- `/contacto.php`
- `/ingreso.php`
- `/pool.php?id=grupo-geisha-42`
- `/historias_productor.php`
- `/nosotros.php`
- `/api/pools.php`
- `/api/pool.php?id=grupo-geisha-42`
- `/api/productores.php`
- `/salud.php`

`/api/pools.php`, `/api/pool.php` y `/api/productores.php` responden con `application/json`.

## Evidencia de Sesion y CSRF

`/ingreso.php` devuelve cookie de sesion `surcos_session` y renderiza token CSRF en el formulario. La clase `Sesion` configura `httponly` y `samesite=Lax`; `ProteccionCsrf` valida tokens con `hash_equals`.

## Evidencia de Sintaxis y Limpieza

Comando:

```powershell
$errores = @(); foreach ($archivo in (rg --files -g '*.php')) { $salida = & 'C:\xampp\php\php.exe' -l $archivo 2>&1; if ($LASTEXITCODE -ne 0) { $errores += $salida } }; if ($errores.Count -gt 0) { $errores; exit 1 } else { 'php -l OK: todos los PHP sin errores de sintaxis' }
```

Resultado:

```text
php -l OK: todos los PHP sin errores de sintaxis
```

Comando:

```powershell
git diff --check
```

Resultado: sin errores de whitespace. Solo se muestran avisos normales de conversion LF/CRLF en Windows.

Busqueda de secretos y dependencias externas:

```powershell
rg -n "<patrones_sensibles_configurados>" . -g '!.git/**' -g '!.env' -g '!almacenamiento/sesiones/**'
```

Resultado: sin coincidencias.

## Flujo Manual Para El Profesor

1. Abrir `/` y leer la propuesta: compradores se unen a pools y productores publican lotes.
2. Entrar a un pool desde `Mercado de Pools`.
3. Iniciar sesion como `comprador@surcos.pa` / `Surcos123!`.
4. Agregar la cantidad minima a la Bandeja de Pools.
5. Confirmar con metodo de pago simulado.
6. Revisar `/historial_pools.php`.
7. Entrar a `/admin/` con `admin@surcos.pa` / `Admin123!`.
8. Revisar solicitudes, aprobar una solicitud nueva y confirmar que la clave temporal se muestra una sola vez.
9. Revisar `/productor/` con `productor@surcos.pa` / `Surcos123!`.
10. Probar los web services `/api/pools.php`, `/api/pool.php?id=grupo-geisha-42` y `/api/productores.php`.
11. Probar `/salud.php` para confirmar PHP, sesiones y MySQL.

## Limites Declarados

- El web service implementado es REST/JSON, no SOAP.
- Los pagos son simulados por seguridad y alcance academico.
- No hay correo real ni pasarela real de pago.
- `referencia_legacy/` no forma parte de la demo oficial.
- Supabase/PostgreSQL quedo fuera del camino principal porque la rubrica pide MySQL.

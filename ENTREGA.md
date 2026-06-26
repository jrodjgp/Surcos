# Entrega Congelada Surcos

Este documento es el punto de control final antes de hacer commit, push o entregar el proyecto. No agrega funcionalidad; define que se considera listo y que queda fuera de alcance.

## Estado Objetivo

Surcos debe entregarse como una app PHP/MySQL funcional y defendible para Proyecto 2:

- PHP visible y sustancial.
- HTML5 semantico.
- CSS3 propio.
- MySQL/MariaDB con mas de una tabla.
- MVC liviano.
- Sesiones y cookies.
- CSRF y validacion de formularios.
- Procedimientos almacenados.
- Web services REST/JSON.
- Flujo comprador, productor y admin demostrable.

## Ruta Oficial

La app oficial se prueba desde:

```text
http://surcos.local/
```

El document root de Apache debe apuntar a:

```text
C:\Users\jose\Documents\VS\Surcos\publico
```

Los archivos legacy en `referencia_legacy/` son referencia historica, no demo oficial.

## Archivos de Referencia Final

- `README.md`: instalacion, rutas, credenciales y resumen tecnico.
- `PRODUCT.md`: definicion de producto y posicionamiento.
- `DESIGN.md`: criterio visual.
- `QA.md`: evidencia de rubrica y comandos verificados.
- `DEMO.md`: recorrido exacto para presentar o probar.
- `ENTREGA.md`: checklist final de congelacion.
- `base_datos/README.md`: orden de importacion y notas de datos.

## Checklist Antes de Commit

1. Revisar estado de Git:

```powershell
git status --short
```

2. Confirmar que `.env` no esta trackeado:

```powershell
git check-ignore -v .env
```

3. Confirmar que sesiones locales no estan trackeadas:

```powershell
git check-ignore -v almacenamiento/sesiones/sess_*
```

4. Validar sintaxis PHP:

```powershell
$errores = @(); foreach ($archivo in (rg --files -g '*.php')) { $salida = & 'C:\xampp\php\php.exe' -l $archivo 2>&1; if ($LASTEXITCODE -ne 0) { $errores += $salida } }; if ($errores.Count -gt 0) { $errores; exit 1 } else { 'php -l OK: todos los PHP sin errores de sintaxis' }
```

5. Validar whitespace:

```powershell
git diff --check
```

6. Buscar secretos o rastros sensibles:

```powershell
rg -n "<patrones_sensibles_configurados>" . -g '!.git/**' -g '!.env' -g '!almacenamiento/sesiones/**'
```

7. Validar datos demo:

```powershell
& 'C:\xampp\mysql\bin\mysql.exe' -u root surcos --batch --raw --execute="source C:/Users/jose/Documents/VS/Surcos/base_datos/004_verificacion_demo.sql"
```

8. Probar rutas principales:

```text
/
/contacto.php
/ingreso.php
/pool.php?id=grupo-geisha-42
/bandeja.php
/historial_pools.php
/historias_productor.php
/nosotros.php
/productor/
/admin/
/api/pools.php
/api/pool.php?id=grupo-geisha-42
/api/productores.php
/salud.php
```

## Criterios de No Tocar

Durante el freeze no se recomienda:

- Cambiar el stack.
- Cambiar MySQL por Supabase/PostgreSQL.
- Rehacer la UI completa.
- Cambiar nombres de rutas.
- Agregar frameworks.
- Agregar JavaScript nuevo innecesario.
- Agregar pagos reales.
- Agregar correo real.
- Modificar tablas sin una razon critica.
- Cambiar credenciales demo sin actualizar todos los documentos.

## Cambios Permitidos

Solo se aceptan:

- Fixes de bug comprobado.
- Correcciones de seguridad.
- Correcciones de texto o documentacion.
- Ajustes visuales menores sin cambiar flujos.
- Correcciones del seed demo.
- Fixes para que la rubrica se pueda probar mejor.

## Secuencia de Demo Recomendada

1. Landing: explicar que compradores se unen a pools y productores publican lotes.
2. Pool: mostrar precio, tramos, progreso y cierre.
3. Comprador: login, agregar a Bandeja, confirmar pago simulado, revisar historial.
4. Productor: login, revisar panel y publicar lote simple.
5. Historias: mostrar productores y pools conectados.
6. Nosotros: explicar problema de intermediarios y modelo Surcos.
7. Admin: revisar solicitudes, aprobar y verificar bitacora.
8. WS: abrir JSON de pools/productores.
9. Salud: confirmar PHP, sesiones y MySQL sin exponer secretos.

## Riesgos Residuales Aceptados

- No hay integracion real de pagos.
- No hay correo real.
- No hay hosting cloud final dentro del freeze local.
- El web service es REST/JSON, no SOAP.
- El diseno puede seguir puliendose para portafolio, pero la entrega academica ya cubre la rubrica principal.

## Mensaje de Commit Sugerido

```text
Finalize Surcos PHP marketplace demo
```

Antes de commitear, revisar manualmente que no se este agregando `.env`, sesiones locales, capturas temporales ni archivos generados fuera de alcance.

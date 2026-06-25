# PRODUCT: Surcos

## Definicion corta

Surcos es un marketplace agricola panameno donde compradores se unen a pools de compra colectiva y productores publican lotes de cosecha para vender de forma mas directa.

## Problema

La cadena de alimentos en Panama puede dejar demasiado valor en manos de intermediarios. Granjeros, ganaderos y productores agricolas no siempre tienen acceso directo a compradores de volumen como restaurantes, hoteles, supermercados, cafeterias, distribuidores pequenos o grupos de consumo organizados. Al mismo tiempo, esos compradores terminan pagando mas por productos cuyo origen y disponibilidad no siempre son claros.

## Propuesta

Surcos organiza demanda antes de mover producto:

- El productor publica un lote con origen, volumen, precio grupal, fecha de cierre y modelo de entrega.
- Los compradores se comprometen a un pool.
- Cuando el pool alcanza volumen suficiente, el productor tiene una venta mas predecible y el comprador obtiene un precio mas directo.
- La plataforma conserva la trazabilidad basica del compromiso, el pago simulado y la actividad del usuario.

## Inspiracion conceptual

El modelo se inspira en la logica de compra grupal de Pinduoduo: conectar productores rurales con compradores agregados para mejorar volumen, precio y acceso. En Surcos, esa idea se adapta a Panama, al mercado agricola local y a las restricciones academicas del proyecto: PHP, HTML5, CSS3, MySQL/MariaDB, sesiones y MVC.

## Usuarios

- Productores agricolas y ganaderos que necesitan publicar lotes y reducir incertidumbre comercial.
- Restaurantes, hoteles, supermercados y negocios que compran alimentos con frecuencia.
- Compradores organizados que quieren mejores precios por volumen.
- Administradores de Surcos que revisan solicitudes, aprueban accesos y mantienen el marketplace ordenado.

## Tono

Surcos debe sentirse panameno, agricola, serio y usable. La identidad puede tener caracter editorial, de registro de cosechas y comercio directo, pero la informacion importante siempre gana: precio, progreso, cierre, origen, productor, accion y estado.

## Anti-referencias

- No debe parecer portal gubernamental.
- No debe parecer museo o postal patriotica.
- No debe parecer ecommerce generico sin pools.
- No debe parecer terminal financiera cripto.
- No debe esconder precios, fechas o llamadas a la accion bajo decoracion.

## Restricciones del proyecto

- PHP 8 visible y sustancial.
- HTML5 semantico.
- CSS3 sin frameworks nuevos.
- MySQL/MariaDB en XAMPP.
- MVC liviano.
- Sesiones, CSRF, PDO y salida escapada.
- Al menos un procedimiento almacenado y un web service.
- Pagos simulados, nunca pagos reales ni datos sensibles completos.

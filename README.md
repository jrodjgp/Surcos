# Surcos

Surcos es un sitio web estatico para un marketplace agricola de compra grupal en Panama. La propuesta central es conectar productores directamente con consumidores mediante pools de compra, reduciendo intermediarios, mejorando el ingreso del agricultor y ofreciendo precios mas claros al comprador final.

El proyecto nace de una necesidad concreta descrita en la pagina Nosotros: en la cadena agricola tradicional, varios intermediarios pueden capturar una parte importante del precio final. Surcos propone un modelo donde el productor publica su cosecha, los compradores se comprometen a un volumen colectivo, el pool se completa y la entrega se coordina por nodo o despacho.

## Objetivo Academico

Este sitio fue preparado para el Proyecto 1 de Desarrollo de Software VII. Cubre los puntos principales del enunciado:

- Estructura HTML5 con `header`, `nav`, `main`, `section`, `article`, `aside` y `footer`.
- Estilos propios con CSS3.
- Uso de Bootstrap 5 para componentes, grilla, formularios y validacion visual.
- POO en JavaScript mediante clases para modelos, formularios, configuracion y componentes.
- Diseno responsive con varias paginas navegables.
- Formulario de contacto y formulario de publicacion de cosecha con validacion.

## Necesidad Que Soluciona

Surcos busca reducir la distancia entre finca y consumidor. El modelo de compra grupal permite:

- Que el productor publique lotes, precio minimo grupal, ubicacion y ventana de cosecha.
- Que los compradores se unan a pools activos para alcanzar volumen colectivo.
- Que el precio mejore cuando el pool alcanza su objetivo.
- Que la entrega se coordine desde nodos o mediante despacho.
- Que el usuario pueda consultar pedidos, pagos, perfil, configuracion y actividad del terminal.

## Paginas Principales

- `marketplace_terminal.html`: pagina principal del marketplace, con vista de comprador, pools activos y panel de productor para publicar nueva cosecha.
- `pool_detail.html`: detalle de un pool especifico, con precio, progreso, cobertura, modelo de entrega y accion para unirse.
- `historias_productor.html`: pagina editorial que presenta la historia de un productor y lotes relacionados.
- `nosotros.html`: explica la necesidad, el modelo de pools, la comparacion contra la cadena tradicional, el equipo y las provincias conectadas.
- `contacto.html`: formulario de contacto con validacion HTML5, Bootstrap y logica POO en JavaScript.
- `mi_terminal_dashboard.html`: panel de actividad del usuario, manifiestos, ordenes y estado operativo.
- `historial_ordenes.html`: archivo de ordenes y filtros de estado.
- `metodos_pago.html`: vista de metodos de pago registrados.
- `perfil.html`: perfil de usuario y actividad reciente.
- `configuracion.html`: ajustes del terminal, notificaciones, punto de retiro, umbral de compromiso y unidades de visualizacion.

## Estructura Del Proyecto

```text
Surcos/
  css/
    styles.css
    navbar.css
    marketplace.css
    dashboard.css
    contacto.css
    nosotros.css
    mapa.css
  img/
    panama-provincias.svg
  js/
    componentes/
      CajonLateral.js
    formularios/
      FormularioContacto.js
      FormularioCosecha.js
    modelos/
      Contacto.js
      Cosecha.js
    configuracion.js
    contacto.js
    global.js
    marketplace.js
  *.html
```

## POO En JavaScript

El proyecto usa clases para mantener la logica modular:

- `Cosecha`: representa una cosecha publicada por un productor y calcula ingreso estimado.
- `Contacto`: representa un mensaje enviado desde el formulario de contacto.
- `FormularioCosecha`: controla validacion, envio simulado y mensajes del formulario de publicacion.
- `FormularioContacto`: controla validacion, envio simulado e insignia del contacto.
- `CajonLateral`: controla el menu lateral "Mi Terminal".
- `ConfiguracionTerminal`: controla el slider de umbral y el cambio entre unidades metricas e imperiales.

## Estilo Visual

La identidad visual usa una paleta agricola y de terminal operativo:

- Verde hoja para autoridad, navegacion y marca.
- Terracota para acciones principales.
- Ocre para progreso, tiempo y acentos.
- Fondos calidos tipo yeso/tierra para mantener relacion con el sector agricola.

El tono visual mezcla marketplace moderno, producto agricola premium y tablero operativo.

## Formularios

El proyecto incluye dos formularios importantes:

- Contacto: `contacto.html`
  - Nombre, correo, telefono, tipo de usuario, asunto, mensaje y consentimiento.
  - Preparado con atributo `data-netlify="true"`.
  - Validacion HTML5, Bootstrap y JavaScript.

- Publicar Nueva Cosecha: `marketplace_terminal.html`
  - Producto, variedad/lote, cantidad, precio minimo, ubicacion, ventana y modelo de entrega.
  - Validacion HTML5, Bootstrap y POO con la clase `Cosecha`.

## Como Ejecutarlo

Es un proyecto estatico. Puede abrirse directamente desde `marketplace_terminal.html` en el navegador o publicarse en un hosting estatico gratuito como Netlify.

No requiere instalacion de dependencias locales.

## Estado Para Presentacion

El sitio ya cuenta con varias paginas, navegacion, formularios, estructura HTML5, estilos propios, Bootstrap y POO en JavaScript. Pendientes recomendados antes de la entrega final:

- Crear e integrar un logo propio.
- Revisar pequenos enlaces de demostracion o acciones no persistentes.
- Optimizar el mapa SVG si se desea reducir el peso de las paginas.

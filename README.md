<div align="center">

# 🌱 Surcos

**Marketplace agricola de compras grupales que conecta productores panameños directamente con consumidores.**

[![Live Demo](https://img.shields.io/badge/Live%20Demo-GitHub%20Pages-22863a?style=for-the-badge&logo=github)](https://jrodjgp.github.io/surcos)
[![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white)](https://developer.mozilla.org/es/docs/Web/HTML)
[![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white)](https://developer.mozilla.org/es/docs/Web/CSS)
[![Bootstrap](https://img.shields.io/badge/Bootstrap_5-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)](https://getbootstrap.com/)
[![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)](https://developer.mozilla.org/es/docs/Web/JavaScript)

[Ver demo](https://jrodjgp.github.io/surcos) · [Reportar problema](https://github.com/jrodjgp/surcos/issues) · [Solicitar mejora](https://github.com/jrodjgp/surcos/issues)

</div>

---

## Problema

En la cadena agricola tradicional de Panama, varios intermediarios pueden capturar entre **40-60% del precio final** (MIDA, 2023). El productor recibe menos por su cosecha y el consumidor termina pagando mas por el mismo producto.

Surcos reduce esa friccion organizando compradores en **pools de compra**. Cuando un pool alcanza su volumen minimo, se activa un mejor precio grupal para todos. Si no se completa el pool, no se cobra la orden.

La experiencia esta pensada como un terminal agricola: los productores publican lotes de cosecha, los compradores se comprometen a una cantidad compartida y la entrega se coordina por nodos de retiro o despacho directo.

---

## Alcance Academico

Este sitio fue preparado para **Proyecto 1, Desarrollo de Software VII**. Cubre los puntos principales del entregable:

- Estructura semantica HTML5 con `header`, `nav`, `main`, `section`, `article`, `aside` y `footer`
- CSS3 con variables de diseño, Flexbox, Grid, animaciones y estilos responsivos
- Bootstrap 5 para grilla, formularios, estados de validacion y componentes base
- Programacion orientada a objetos en JavaScript mediante clases ES6
- Varias paginas conectadas por navegacion y cajon lateral
- Logo propio, identidad visual, footers con redes sociales y contenido editorial
- Formulario de contacto validado y formulario de publicacion de cosecha
- Compatibilidad con hosting estatico como GitHub Pages, Netlify o Cloudflare Pages

---

## Funcionalidades

- **Marketplace activo**: muestra pools abiertos por producto, provincia, progreso y fecha limite
- **Panel de productor**: publica una cosecha con precio minimo, volumen, ubicacion, ventana y modelo de entrega
- **Detalle de pool**: permite comprometerse, cancelar compromiso, ver progreso, precio, cobertura y entrega
- **Mi Terminal**: dashboard del usuario con ordenes, manifiestos, pagos, perfil y configuracion
- **Historial de ordenes**: conserva el estado de cada pool sin duplicar registros al entrar y salir varias veces
- **Metodos de pago**: permite seleccionar metodo principal y agregar tarjetas demo
- **Perfil**: actualiza datos de usuario, provincia, nodo de retiro y acciones de datos
- **Configuracion**: controla notificaciones, umbral de compromiso, nodo de retiro y unidades metricas/imperiales
- **Historias de productor**: pagina editorial con perfiles, fincas y lotes relacionados
- **Contacto**: formulario validado con HTML5, Bootstrap y JavaScript orientado a objetos
- **Mapa SVG compartido**: recurso unico para visualizar cobertura de provincias sin repetir el SVG completo en cada pagina
- **Reloj y cinta dinamica**: terminal con hora local y datos vivos tomados del estado demo

---

## Paginas

| Pagina | Proposito |
|---|---|
| `index.html` | Entrada principal: marketplace de pools y formulario de publicacion de cosecha |
| `pool_detail.html` | Detalle de pool con progreso, precio, mapa, compromiso y cancelacion |
| `historias_productor.html` | Historia editorial de productores y lotes relacionados |
| `nosotros.html` | Problema, solucion, equipo, cobertura e impacto |
| `contacto.html` | Formulario de contacto para compradores, productores e instituciones |
| `mi_terminal_dashboard.html` | Dashboard del usuario con ordenes, metricas y manifiestos |
| `historial_ordenes.html` | Archivo de ordenes con filtros por estado |
| `metodos_pago.html` | Metodos guardados, seleccion principal y nuevo metodo demo |
| `perfil.html` | Perfil, preferencias, actividad y acciones de datos |
| `configuracion.html` | Ajustes de terminal, nodo de retiro, notificaciones, umbral y unidades |
| `ingreso.html` | Inicio de sesion demo |
| `registro.html` | Registro demo de comprador o productor |
| `marketplace_terminal.html` | Redireccion de compatibilidad hacia `index.html` |

---

## Stack Tecnico

| Capa | Tecnologia |
|---|---|
| Marcado | HTML5 semantico |
| Estilos | CSS3, variables, Flexbox, Grid y animaciones |
| Framework | Bootstrap 5 |
| Logica | JavaScript vanilla con clases ES6 |
| Persistencia demo | `localStorage` |
| Formularios | HTML5, Bootstrap validation y estructura lista para Netlify |
| Hosting | GitHub Pages o cualquier hosting estatico |

---

## Ejecucion Local

No hay paso de compilacion ni dependencias externas.

```bash
git clone https://github.com/jrodjgp/surcos.git
cd surcos
```

Abre `index.html` en el navegador. Tambien puede publicarse directamente en GitHub Pages, Netlify, Cloudflare Pages, AWS S3 + CloudFront o cualquier servicio de archivos estaticos.

---

## Estructura

```text
surcos/
├── css/
│   ├── styles.css              # Variables globales y base visual
│   ├── navbar.css              # Navegacion, footer, cajon lateral y avisos demo
│   ├── marketplace.css         # Marketplace, detalle de pool e historias
│   ├── dashboard.css           # Dashboard, perfil, historial, pagos y configuracion
│   ├── contacto.css            # Pagina de contacto
│   ├── nosotros.css            # Pagina nosotros
│   └── mapa.css                # Contenedores y estados del mapa
├── img/
│   ├── logo-surcos.svg
│   ├── panama-provincias.svg
│   └── imagenes del sitio
├── js/
│   ├── componentes/
│   │   └── CajonLateral.js
│   ├── datos/
│   │   └── datosIniciales.js
│   ├── formularios/
│   │   ├── FormularioContacto.js
│   │   └── FormularioCosecha.js
│   ├── modelos/
│   │   ├── Contacto.js
│   │   └── Cosecha.js
│   ├── paginas/
│   │   ├── autenticacion.js
│   │   ├── historial.js
│   │   ├── pagos.js
│   │   ├── perfil.js
│   │   ├── pool.js
│   │   └── terminal.js
│   ├── servicios/
│   │   ├── AutenticacionSurcos.js
│   │   ├── EstadoSurcos.js
│   │   ├── GruposCompraSurcos.js
│   │   ├── MetodosPagoSurcos.js
│   │   ├── OrdenesSurcos.js
│   │   └── PublicacionesProductorSurcos.js
│   ├── configuracion.js
│   ├── contacto.js
│   ├── global.js
│   └── marketplace.js
├── index.html
├── pool_detail.html
├── historias_productor.html
├── nosotros.html
├── contacto.html
├── mi_terminal_dashboard.html
├── historial_ordenes.html
├── metodos_pago.html
├── perfil.html
├── configuracion.html
├── ingreso.html
├── registro.html
└── marketplace_terminal.html
```

---

## Arquitectura JavaScript

Surcos mantiene la logica separada del marcado mediante clases reutilizables:

| Clase | Responsabilidad |
|---|---|
| `Cosecha` | Modelo de cosecha y calculo de ingreso estimado |
| `Contacto` | Modelo de mensaje de contacto |
| `FormularioCosecha` | Validacion y publicacion demo de cosechas |
| `FormularioContacto` | Validacion y envio demo del formulario de contacto |
| `CajonLateral` | Apertura y cierre del menu lateral Mi Terminal |
| `EstadoSurcos` | Estado demo persistido en `localStorage` |
| `AutenticacionSurcos` | Sesion demo, registro e inicio de sesion |
| `GruposCompraSurcos` | Lectura, compromiso y cancelacion de pools |
| `OrdenesSurcos` | Historial, estados de entrega y orden activa |
| `MetodosPagoSurcos` | Metodos de pago demo y seleccion principal |
| `PublicacionesProductorSurcos` | Publicaciones creadas desde el formulario de cosecha |
| `ConfiguracionTerminal` | Umbral, unidades, notificaciones y reinicio de demo |
| `AccionesDemostracion` | Avisos para acciones simuladas |
| `RelojTerminal` | Hora local dinamica del marketplace |
| `CintaTerminal` | Datos dinamicos de la cinta del terminal |

---

## Formularios

Surcos incluye dos formularios clave:

- **Contacto** (`contacto.html`)
  - Nombre, correo, telefono, tipo de usuario, asunto, mensaje y consentimiento
  - Validacion HTML5, Bootstrap y retroalimentacion con `FormularioContacto`
  - Marcado listo para Netlify mediante `data-netlify="true"`

- **Publicar nueva cosecha** (`index.html`)
  - Producto, variedad/lote, cantidad, precio minimo, ubicacion, ventana de cosecha y modelo de entrega
  - Validacion HTML5, Bootstrap y logica OOP con `FormularioCosecha` y `Cosecha`

---

## Sistema Visual

Surcos usa una paleta agricola sobria con lenguaje de terminal operativo:

| Rol | Color | Hex |
|---|---|---|
| Marca y navegacion | Verde hoja | `#1A5C2A` |
| Acciones principales | Terracota | `#C0522A` |
| Progreso y tiempo | Ocre | `#C07A2A` |
| Fondo | Tierra clara | `#F5F1E8` |

La tipografia mezcla titulares editoriales con cuerpo sans-serif y numeros de estilo terminal para precios, cantidades, porcentajes y datos operativos.

---

## Estado Actual

El sitio ya cumple con la estructura base del entregable: varias paginas, navegacion, logo, footer, HTML5 semantico, CSS3, Bootstrap, formularios validados, comportamiento responsivo y JavaScript orientado a objetos. Tambien incluye un flujo demo sin base de datos para presentar la experiencia completa de comprador y productor.

Pendientes recomendados antes de la sustentacion:

- Publicar el sitio en el hosting elegido y probar la URL final
- Revisar el contenido oral de la presentacion con el flujo principal: registro, pool, cancelacion, historial, cosecha y contacto
- Comprimir imagenes grandes si el sitio se siente pesado en internet real
- Confirmar referencias academicas o institucionales que el grupo quiera citar en clase

---

<div align="center">
Hecho en Panama 🇵🇦 · <a href="https://jrodjgp.github.io/surcos">jrodjgp.github.io/surcos</a>
</div>

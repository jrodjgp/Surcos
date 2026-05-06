<div align="center">

# 🌱 Surcos

**Agricultural group-buying marketplace connecting Panamanian farmers directly with consumers.**

[![Live Demo](https://img.shields.io/badge/Live%20Demo-GitHub%20Pages-22863a?style=for-the-badge&logo=github)](https://jrodjgp.github.io/surcos)
[![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white)](https://developer.mozilla.org/en-US/docs/Web/HTML)
[![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white)](https://developer.mozilla.org/en-US/docs/Web/CSS)
[![Bootstrap](https://img.shields.io/badge/Bootstrap_5-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)](https://getbootstrap.com/)
[![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)](https://developer.mozilla.org/en-US/docs/Web/JavaScript)

[View Demo](https://jrodjgp.github.io/surcos) · [Report Bug](https://github.com/jrodjgp/surcos/issues) · [Request Feature](https://github.com/jrodjgp/surcos/issues)

</div>

---

## The Problem

In Panama's traditional agricultural supply chain, multiple intermediaries capture between **40-60% of the final price** (MIDA, 2023), leaving farmers underpaid and consumers overpaying for the same product.

Surcos cuts the chain by organizing buyers into **purchase pools**. When a pool hits its minimum volume, a better group price activates for everyone. No pool, no charge.

The core idea is simple: farmers publish harvest lots, buyers commit to a shared volume, the pool fills, and delivery is coordinated through pickup nodes or direct dispatch.

---

## Academic Scope

This website was prepared for **Proyecto 1, Desarrollo de Software VII**. It addresses the required deliverable with:

- Semantic HTML5 structure using `header`, `nav`, `main`, `section`, `article`, `aside`, and `footer`
- CSS3 styling with custom design tokens, responsive layouts, Flexbox, Grid, and animations
- Bootstrap 5 for framework usage, layout support, form controls, and validation states
- Object-oriented programming in JavaScript through reusable ES6 classes
- Multiple pages, navigation, footer, visual identity, and contact form
- Static-hosting compatibility for GitHub Pages, Netlify, Cloudflare Pages, or similar hosting

---

## Features

- **Active pool browser**: see open group purchases by product, province, progress, and deadline
- **Producer panel**: publish a harvest lot with minimum price, available volume, location, delivery model, and harvest window
- **Pool detail view**: track progress, coverage map, pricing, delivery model, and join action
- **User terminal dashboard**: order manifests, portfolio value, delivery status, and operational data
- **Order history**: archive view for past purchases and order states
- **Payment methods**: saved cards, preferred payment method, and backup method view
- **Profile page**: user identity, preferences, activity feed, and data actions
- **Settings page**: notification preferences, pickup node, commitment threshold, and metric/imperial visualization
- **Producer stories**: editorial page featuring farmer profiles and related lots
- **Contact form**: validated with HTML5, Bootstrap, and OOP JavaScript
- **Interactive SVG map**: Panama province coverage with active/inactive visual states
- **CSS-only animated ticker**: real-time data terminal aesthetic, zero JavaScript for the ticker itself
- **Demo action feedback**: placeholder actions respond with a terminal-style status message instead of silently doing nothing

---

## Pages

| Page | Purpose |
|---|---|
| `marketplace_terminal.html` | Main entry point: buyer pool browser plus producer publishing panel |
| `pool_detail.html` | Detailed pool page with progress, pricing, coverage, and join action |
| `historias_productor.html` | Editorial producer story and related harvest lots |
| `nosotros.html` | Problem, solution model, team, province coverage, and institutional references |
| `contacto.html` | Contact form for producers, buyers, institutions, and support requests |
| `mi_terminal_dashboard.html` | User terminal dashboard with orders, manifests, and metrics |
| `historial_ordenes.html` | Order archive and status filters |
| `metodos_pago.html` | Saved payment methods and billing options |
| `perfil.html` | User profile, activity feed, and data actions |
| `configuracion.html` | Terminal settings, pickup node, notifications, threshold, and units |

---

## Tech Stack

| Layer | Technology |
|---|---|
| Markup | HTML5 semantic elements |
| Styles | CSS3 custom properties, Flexbox, Grid, keyframe animations |
| Framework | Bootstrap 5 |
| Logic | Vanilla JavaScript, ES6 classes, OOP architecture |
| Hosting | GitHub Pages or any static hosting |
| Forms | Netlify-ready contact form with `data-netlify="true"` |

---

## Getting Started

No build step. No dependencies. Clone and open.

```bash
git clone https://github.com/jrodjgp/surcos.git
cd surcos
```

Open `marketplace_terminal.html` in your browser. That is the main entry point.

> Works with any static hosting: GitHub Pages, Netlify, Cloudflare Pages, AWS S3 + CloudFront.

---

## Project Structure

```text
surcos/
├── css/
│   ├── styles.css          # Global design tokens
│   ├── navbar.css          # Header, footer, side drawer, demo notifications
│   ├── marketplace.css     # Marketplace, pool detail, producer story styles
│   ├── dashboard.css       # Dashboard, profile, history, payments, settings
│   ├── contacto.css        # Contact page styles
│   ├── nosotros.css        # About / problem-solution page styles
│   └── mapa.css            # Panama SVG map states and contexts
├── js/
│   ├── componentes/
│   │   └── CajonLateral.js         # Slide-in "Mi Terminal" side menu
│   ├── formularios/
│   │   ├── FormularioContacto.js   # Contact form validation and feedback
│   │   └── FormularioCosecha.js    # Harvest form validation and feedback
│   ├── modelos/
│   │   ├── Contacto.js             # Contact message model
│   │   └── Cosecha.js              # Harvest listing model and income estimate
│   ├── configuracion.js            # Threshold slider and metric/imperial toggle
│   ├── contacto.js                 # Contact page initializer
│   ├── global.js                   # Shared drawer and demo-action behavior
│   └── marketplace.js              # Marketplace form initializer
├── img/
│   └── panama-provincias.svg
├── marketplace_terminal.html
├── pool_detail.html
├── historias_productor.html
├── nosotros.html
├── contacto.html
├── mi_terminal_dashboard.html
├── historial_ordenes.html
├── metodos_pago.html
├── perfil.html
└── configuracion.html
```

---

## JavaScript Architecture

Surcos uses ES6 classes to keep logic modular and separated from static markup:

| Class | Responsibility |
|---|---|
| `Cosecha` | Harvest listing model, including estimated producer income |
| `Contacto` | Contact message model |
| `FormularioCosecha` | Harvest form validation, simulated submit, and feedback |
| `FormularioContacto` | Contact form validation, simulated submit, and badge update |
| `CajonLateral` | Side menu open/close behavior |
| `ConfiguracionTerminal` | Commitment threshold slider and metric/imperial toggle |
| `AccionesDemo` | Shared demo feedback for placeholder actions |

---

## Forms

Surcos includes two key validated forms:

- **Contact form** (`contacto.html`)
  - Name, email, phone, user type, subject, message, and consent checkbox
  - Netlify-ready through `data-netlify="true"`
  - HTML5 constraints, Bootstrap validation states, and OOP JavaScript feedback

- **Harvest publishing form** (`marketplace_terminal.html`)
  - Product, variety/lot, quantity, minimum group price, farm location, harvest window, and delivery model
  - HTML5 constraints, Bootstrap validation states, and OOP JavaScript through `FormularioCosecha` and `Cosecha`

---

## Design System

Surcos uses a warm agricultural palette with a terminal data aesthetic:

| Role | Color | Hex |
|---|---|---|
| Brand / Navigation | Leaf green | `#1A5C2A` |
| Primary actions | Terracotta | `#C0522A` |
| Progress / Time | Ochre | `#C07A2A` |
| Background | Plaster / earth | `#F5F1E8` |

Typography mixes editorial serif headlines, clean sans-serif body copy, and monospace-style numeric treatments for prices, IDs, percentages, and operational data. The result is a marketplace-meets-field-terminal identity.

---

## Current Presentation Status

The site already includes multiple pages, navigation, semantic HTML5 structure, Bootstrap, responsive CSS, forms, and JavaScript OOP. Before final delivery, the highest-value polish items are:

- Add a custom logo created by the team
- Decide which demo actions should become fully functional for the presentation
- Add `index.html` as the canonical entry point if publishing to GitHub Pages
- Optimize repeated SVG map usage if page weight becomes a concern

---

## Roadmap

- [ ] Add `index.html` as the canonical entry point
- [ ] Integrate IMA (Instituto de Mercadeo Agropecuario) reference prices
- [ ] Implement pool persistence with a backend such as Supabase or PHP/MySQL
- [ ] Add authentication with buyer/producer role switching
- [ ] Optimize repeated map and image assets
- [ ] Add real downloadable exports for CSV/data actions

---

## License

Distributed under the MIT License. See `LICENSE` for details.

---

<div align="center">
Built in Panama 🇵🇦 · <a href="https://jrodjgp.github.io/surcos">jrodjgp.github.io/surcos</a>
</div>

<?php

declare(strict_types=1);

require dirname(__DIR__) . '/aplicacion/Arranque.php';

$tituloPagina = 'Surcos | Base PHP';
$descripcionPagina = 'Base PHP de Surcos para Supabase, admin y bandeja de pools.';
$paginaActiva = 'marketplace';
$estilosExtra = ['marketplace.css', 'mapa.css'];

renderizar_vista('parciales/cabecera.php', compact('tituloPagina', 'descripcionPagina', 'estilosExtra'));
renderizar_vista('parciales/navegacion.php', compact('paginaActiva'));
?>

<main class="split">
  <section class="hero-col">
    <div class="arch">
      <img alt="Cafe de especialidad listo para compra grupal"
        src="https://images.unsplash.com/photo-1447933601403-0c6688de566e?w=900&q=85&fit=crop" />
    </div>
    <div class="hero-meta">
      <p>Base PHP preparada para la migracion</p>
      <p>"Del prototipo estatico al terminal con datos reales."</p>
    </div>
  </section>

  <section class="terminal-col">
    <div class="terminal-inner">
      <h1 class="pool-title">Surcos PHP v1</h1>
      <div class="pool-meta">
        <span>Ejecucion: PHP <?= escapar(PHP_VERSION) ?></span>
        <span class="divider"></span>
        <span class="batch">Fase 1</span>
      </div>

      <div class="terminal-box">
        <div class="ver">BASE v.1.0</div>
        <div class="status-row">
          <div>
            <div class="label">Estado</div>
            <div class="val">PHP activo</div>
          </div>
          <div class="text-right">
            <div class="label">Siguiente paso</div>
            <div class="val terra">Base de datos</div>
            <div class="deadline">Supabase + PDO</div>
          </div>
        </div>

        <div class="progress-labels">
          <span class="pool-cantidad">Fase 1/11</span>
          <span class="pool-porcentaje">9%</span>
        </div>
        <div class="progress-track">
          <i class="progress-fill" style="--pool-progress:9%; width:9%"></i>
          <div class="mark" style="left:33.3%"></div>
          <div class="mark" style="left:66.6%"></div>
        </div>

        <div class="pricing">
          <div>
            <div class="label">Estructura</div>
            <span class="market-price tab">publico/<small>raiz</small></span>
          </div>
          <div>
            <div class="label ochre">Seguridad base</div>
            <span class="pool-price tab">CSRF<small>+sesion</small></span>
          </div>
        </div>

        <a class="btn-pool" href="<?= escapar(url_para('/salud.php')) ?>">Verificar PHP
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M5 12h14"></path>
            <path d="M13 6l6 6-6 6"></path>
          </svg>
        </a>
      </div>

      <div class="details">
        <div>
          <h3>Incluido</h3>
          <p class="text">Bootstrap PHP, sesiones seguras, token CSRF, ayudantes de rutas y vistas parciales compartidas para cabecera, navegacion, mensajes y pie.</p>
        </div>
        <div>
          <h3>Proximas fases</h3>
          <div class="log-row"><span>Fase 2</span><span>PostgreSQL/Supabase</span></div>
          <div class="log-row"><span>Fase 3</span><span>Solicitudes reales</span></div>
          <div class="log-row"><span>Fase 4</span><span>Administracion protegida</span></div>
        </div>
      </div>
    </div>
  </section>
</main>

<?php renderizar_vista('parciales/pie.php'); ?>

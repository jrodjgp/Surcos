<?php
$nombreProductor = $productor['nombre'] ?? 'Productores Surcos';
$nombreCorto = trim((string) preg_replace('/^(Finca|Cooperativa|Apiario)\s+/i', '', (string) $nombreProductor));
$poolHero = $poolPrincipal ?? ($poolsProductor[0] ?? null);
$imagenHero = $poolHero ? imagen_cosecha($poolHero) : url_recurso('img/cosechas/cosecha-generica.jpg');
$avanceHero = $poolHero ? $poolModelo->avance($poolHero) : 0;
$lineasHistoria = array_filter([
    $productor['historia'] ?? null,
    isset($productor['zona'], $productor['provincia'], $productor['responsable'])
        ? 'Desde ' . $productor['zona'] . ', ' . $productor['provincia'] . ', ' . $productor['responsable'] . ' organiza lotes para vender con menos intermediarios y con demanda visible antes de mover producto.'
        : null,
    $poolHero
        ? 'Su pool activo de ' . $poolHero['producto'] . ' muestra precio grupal, cupo, origen, nodo y cierre antes de que el comprador confirme un compromiso.'
        : 'Cuando publica un lote, Surcos lo convierte en una oportunidad clara: origen, volumen, precio y fecha de cierre en un solo registro.',
]);
?>

<main class="pagina-historias">
  <?php if (!empty($errorDatos)): ?>
    <div class="wrap aviso-bd"><?= escapar($errorDatos) ?></div>
  <?php endif; ?>

  <?php if (!$productor): ?>
    <section class="wrap estado-vacio">
      <strong>No hay productores disponibles.</strong>
      <p>Importa las semillas demo para ver historias conectadas a pools.</p>
    </section>
  <?php else: ?>
    <section class="story-hero historia-hero" aria-labelledby="titulo-historia">
      <img src="<?= escapar($imagenHero) ?>" alt="<?= escapar($nombreProductor . ' en Surcos') ?>" />
      <div class="grad"></div>
      <div class="content">
        <div>
          <p class="label">Historia de productor - <?= escapar($productor['provincia']) ?></p>
          <h1 id="titulo-historia"><?= escapar($nombreCorto) ?>.</h1>
        </div>
        <a class="historia-hero-link" href="#pools-productor">Ver pools</a>
      </div>
    </section>

    <section class="editorial historia-editorial">
      <aside class="sidebar-meta">
        <div class="block">
          <h2 class="lbl">Responsable</h2>
          <p class="val"><?= escapar($productor['responsable']) ?></p>
        </div>
        <div class="block">
          <h2 class="lbl">Ubicacion</h2>
          <p class="val"><?= escapar($productor['zona']) ?><br><?= escapar($productor['provincia']) ?></p>
        </div>
        <div class="block">
          <h2 class="lbl">Especialidad</h2>
          <p class="val"><?= escapar($productor['especialidad']) ?></p>
        </div>
      </aside>

      <article class="article">
        <?php foreach ($lineasHistoria as $indice => $parrafo): ?>
          <p class="<?= $indice === 0 ? 'dropcap' : '' ?>"><?= escapar($parrafo) ?></p>
        <?php endforeach; ?>
        <blockquote class="bq">
          <p>"Cada pool convierte una cosecha en una venta mas predecible para el productor y mas clara para el comprador."</p>
        </blockquote>
      </article>

      <aside class="sidebar-detail">
        <div class="batch-card">
          <h2 class="lbl">Registro activo</h2>
          <?php if ($poolHero): ?>
            <div class="batch-row"><span>Pool</span><span><?= escapar($poolHero['producto']) ?></span></div>
            <div class="batch-row"><span>Precio grupal</span><span class="terra"><?= escapar(dinero($poolHero['precio_grupal'])) ?></span></div>
            <div class="batch-row"><span>Avance</span><span><?= escapar($poolHero['personas_actuales'] . '/' . $poolHero['personas_objetivo']) ?> personas</span></div>
            <div class="mini-bar"><i style="width:<?= escapar((string) $avanceHero) ?>%"></i></div>
            <a class="historia-mini-cta" href="<?= escapar(url_para('/pool.php?id=' . $poolHero['id'])) ?>">Ver detalle del pool</a>
          <?php else: ?>
            <p class="historia-vacia">Este productor no tiene pools activos en este momento.</p>
          <?php endif; ?>
        </div>
        <div class="sidebar-img">
          <img src="<?= escapar($imagenHero) ?>" alt="<?= escapar('Producto de ' . $nombreProductor) ?>" />
        </div>
      </aside>
    </section>

    <section class="photo-strip historia-tira" aria-label="Productos y registros de <?= escapar($nombreProductor) ?>">
      <?php
      $galeria = array_slice($poolsProductor, 0, 3);
      if (empty($galeria) && $poolHero) {
          $galeria = [$poolHero];
      }
      while (count($galeria) < 3) {
          $galeria[] = ['producto' => $productor['especialidad'], 'origen' => $productor['zona'], 'categoria' => '', 'id' => ''];
      }
      ?>
      <?php foreach ($galeria as $item): ?>
        <figure>
          <img src="<?= escapar(imagen_cosecha($item)) ?>" alt="<?= escapar(($item['producto'] ?? 'Cosecha') . ' de ' . $nombreProductor) ?>" />
          <figcaption><?= escapar(($item['producto'] ?? $productor['especialidad']) . ' - ' . ($item['origen'] ?? $productor['zona'])) ?></figcaption>
        </figure>
      <?php endforeach; ?>
    </section>

    <section class="productores-relacionados">
      <div class="productores-relacionados-inner">
        <h2>Mas historias</h2>
        <div class="productores-relacionados-grid">
          <a class="productor-relacionado activo" href="<?= escapar(url_para('/historias_productor.php?productor=' . $productor['id'])) ?>">
            <span><?= escapar($productor['provincia']) ?></span>
            <strong><?= escapar($productor['nombre']) ?></strong>
            <small><?= escapar($productor['especialidad']) ?></small>
          </a>
          <?php foreach ($relacionados as $relacionado): ?>
            <a class="productor-relacionado" href="<?= escapar(url_para('/historias_productor.php?productor=' . $relacionado['id'])) ?>">
              <span><?= escapar($relacionado['provincia']) ?></span>
              <strong><?= escapar($relacionado['nombre']) ?></strong>
              <small><?= escapar($relacionado['especialidad']) ?></small>
            </a>
          <?php endforeach; ?>
        </div>
      </div>
    </section>

    <section class="shop" id="pools-productor">
      <div class="shop-inner">
        <div class="shop-header">
          <h2>Pools de <?= escapar($productor['nombre']) ?></h2>
          <a href="<?= escapar(url_para('/')) ?>#pools-activos">Ver mercado completo</a>
        </div>
        <?php if (empty($poolsProductor)): ?>
          <p class="historia-vacia">Este productor no tiene pools activos en este momento.</p>
        <?php else: ?>
          <div class="shop-grid">
            <?php foreach ($poolsProductor as $pool): ?>
              <?php $avance = $poolModelo->avance($pool); ?>
              <article class="shop-card">
                <figure>
                  <img src="<?= escapar(imagen_cosecha($pool)) ?>" alt="<?= escapar($pool['producto'] . ' - ' . $pool['origen']) ?>" />
                </figure>
                <div class="body">
                  <h3 class="sname"><?= escapar($pool['producto']) ?></h3>
                  <p class="lot"><?= escapar($pool['variedad'] . ' - ' . $pool['origen']) ?></p>
                  <div class="sprice tab"><?= escapar(dinero($pool['precio_grupal'])) ?></div>
                  <div class="mini-bar"><i style="width:<?= escapar((string) $avance) ?>%"></i></div>
                  <a class="sbtn" href="<?= escapar(url_para('/pool.php?id=' . $pool['id'])) ?>">Ver pool</a>
                </div>
              </article>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
    </section>
  <?php endif; ?>
</main>

<main class="wrap wrap--md">
  <article class="pool-detalle">
    <div class="pool-detalle-media">
      <img src="<?= escapar(imagen_cosecha($pool)) ?>" alt="<?= escapar($pool['producto'] . ' en Surcos') ?>" />
    </div>
    <div class="pool-detalle-cuerpo">
      <p class="eyebrow"><?= escapar($pool['origen']) ?></p>
      <h1><?= escapar($pool['producto'] . ' - ' . $pool['variedad']) ?></h1>
      <p><?= escapar($pool['productor_nombre']) ?> publica este lote con entrega <?= escapar($pool['modelo_entrega']) ?>.</p>
      <a class="pool-historia-link" href="<?= escapar(url_para('/historias_productor.php?productor=' . $pool['productor_id'])) ?>">Leer historia del productor</a>
      <div class="price-row">
        <span class="price tab"><?= escapar(dinero($pool['precio_vigente'])) ?><small>/<?= escapar($pool['unidad']) ?></small></span>
        <span class="retail tab">Retail: <?= escapar(dinero($pool['precio_mercado'])) ?></span>
      </div>
      <?php if (!empty($pool['siguiente_tramo'])): ?>
        <p class="nota-tramo">Faltan <?= escapar((string) $pool['faltan_siguiente_tramo']) ?> comprador(es) para bajar a <?= escapar(dinero($pool['siguiente_tramo']['precio_unitario'])) ?>/<?= escapar($pool['unidad']) ?>.</p>
      <?php else: ?>
        <p class="nota-tramo">Este pool ya esta en su mejor tramo de precio.</p>
      <?php endif; ?>
      <div class="prog">
        <div class="prog-head">
          <span><?= escapar($pool['personas_actuales'] . '/' . $pool['personas_objetivo']) ?> personas</span>
          <span><?= escapar((string) $avance) ?>%</span>
        </div>
        <div class="bar"><i class="progress-fill" style="--pool-progress:<?= escapar((string) $avance) ?>%"></i></div>
      </div>
      <dl class="detalle-lista">
        <div><dt>Cierre</dt><dd><?= escapar(fecha_hora_corta($pool['fecha_cierre'])) ?></dd></div>
        <div><dt>Entrega</dt><dd><?= escapar(fecha_corta($pool['fecha_entrega'])) ?></dd></div>
        <div><dt>Nodo</dt><dd><?= escapar($pool['nodo_nombre'] ?? 'Por confirmar') ?></dd></div>
      </dl>
      <?php if (!empty($pool['tramos'])): ?>
        <section class="tramos-panel" aria-label="Tramos de precio del pool">
          <h2>Tramos de precio</h2>
          <div class="tramos-grid">
            <?php foreach ($pool['tramos'] as $tramo): ?>
              <?php $activoTramo = (int) $tramo['compradores_minimos'] <= ((int) $pool['personas_actuales'] + 1); ?>
              <div class="<?= $activoTramo ? 'tramo activo' : 'tramo' ?>">
                <span><?= escapar((string) $tramo['compradores_minimos']) ?>+ compradores</span>
                <strong><?= escapar(dinero($tramo['precio_unitario'])) ?></strong>
                <small><?= escapar($tramo['etiqueta']) ?></small>
              </div>
            <?php endforeach; ?>
          </div>
        </section>
      <?php endif; ?>
      <form method="post" action="<?= escapar(url_para('/pool_agregar.php')) ?>" class="pool-compromiso">
        <?= campo_csrf() ?>
        <input type="hidden" name="pool_id" value="<?= escapar($pool['id']) ?>" />
        <label for="cantidadPool">cantidad</label>
        <input id="cantidadPool" name="cantidad" type="number" min="<?= escapar((string) $pool['cantidad_minima']) ?>" step="1" value="<?= escapar((string) $pool['cantidad_minima']) ?>" required />
        <button class="btn-primary" type="submit">Agregar a Bandeja</button>
      </form>
    </div>
  </article>

  <section class="productor-pool-panel">
    <div>
      <p class="eyebrow">Productor</p>
      <h2><?= escapar($pool['productor_nombre']) ?></h2>
      <p><?= escapar($pool['productor_historia'] ?: 'Productor afiliado a Surcos con lotes publicados en el marketplace.') ?></p>
    </div>
    <dl class="detalle-lista">
      <div><dt>Responsable</dt><dd><?= escapar($pool['productor_responsable']) ?></dd></div>
      <div><dt>Zona</dt><dd><?= escapar(($pool['productor_zona'] ?? 'Sin zona') . ', ' . ($pool['productor_provincia'] ?? 'Panama')) ?></dd></div>
      <div><dt>Especialidad</dt><dd><?= escapar($pool['productor_especialidad'] ?? $pool['producto']) ?></dd></div>
    </dl>
  </section>
</main>

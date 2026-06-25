<main class="wrap wrap--md">
  <article class="pool-detalle">
    <div class="pool-detalle-media">
      <img src="<?= escapar(imagen_cosecha($pool)) ?>" alt="<?= escapar($pool['producto'] . ' en Surcos') ?>" />
    </div>
    <div class="pool-detalle-cuerpo">
      <p class="eyebrow"><?= escapar($pool['origen']) ?></p>
      <h1><?= escapar($pool['producto'] . ' - ' . $pool['variedad']) ?></h1>
      <p><?= escapar($pool['productor_nombre']) ?> publica este lote con entrega <?= escapar($pool['modelo_entrega']) ?>.</p>
      <div class="price-row">
        <span class="price tab"><?= escapar(dinero($pool['precio_grupal'])) ?><small>/<?= escapar($pool['unidad']) ?></small></span>
        <span class="retail tab">Retail: <?= escapar(dinero($pool['precio_mercado'])) ?></span>
      </div>
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
      <form method="post" action="<?= escapar(url_para('/pool_agregar.php')) ?>" class="pool-compromiso">
        <?= campo_csrf() ?>
        <input type="hidden" name="pool_id" value="<?= escapar($pool['id']) ?>" />
        <label for="cantidadPool">cantidad</label>
        <input id="cantidadPool" name="cantidad" type="number" min="<?= escapar((string) $pool['cantidad_minima']) ?>" step="1" value="<?= escapar((string) $pool['cantidad_minima']) ?>" required />
        <button class="btn-primary" type="submit">Agregar a Bandeja</button>
      </form>
    </div>
  </article>
</main>

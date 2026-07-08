<main class="wrap wrap--md panel-productor">
  <section class="terminal-head productor-head">
    <div>
      <p class="eyebrow">Panel de Productor</p>
      <h1><?= escapar($productor['nombre']) ?></h1>
      <p class="sub">Revisa tus pools publicados, precio vigente por tramo, progreso y compromisos simulados.</p>
    </div>
    <div class="terminal-actions">
      <a class="btn-outline" href="<?= escapar(url_para('/')) ?>#registro-cosecha">Publicar cosecha</a>
      <a class="btn-outline" href="<?= escapar(url_para('/historias_productor.php?productor=' . $productor['id'])) ?>">Ver historia publica</a>
    </div>
  </section>

  <section class="metric-grid" aria-label="Resumen del productor">
    <article>
      <span>Pools totales</span>
      <strong><?= escapar((string) $metricas['total']) ?></strong>
    </article>
    <article>
      <span>Activos</span>
      <strong><?= escapar((string) $metricas['activos']) ?></strong>
    </article>
    <article>
      <span>Comprometido</span>
      <strong><?= escapar(dinero($metricas['monto_confirmado'])) ?></strong>
    </article>
    <article>
      <span>Proximo cierre</span>
      <strong><?= escapar(fecha_hora_corta($metricas['proximo_cierre'])) ?></strong>
    </article>
  </section>

  <section class="sec-head">
    <h2>Mis pools</h2>
    <span class="badge"><?= escapar((string) count($pools)) ?> registros</span>
  </section>

  <?php if (empty($pools)): ?>
    <div class="estado-vacio">
      <strong>Aun no tienes pools publicados.</strong>
      <p>Publica un lote desde el registro de cosecha para abrir demanda colectiva antes de mover producto.</p>
      <a href="<?= escapar(url_para('/')) ?>#registro-cosecha">Publicar primer pool</a>
    </div>
  <?php else: ?>
    <section class="productor-pools-grid">
      <?php foreach ($pools as $pool): ?>
        <?php $avance = $poolModelo->avance($pool); ?>
        <article class="productor-pool-card">
          <img src="<?= escapar(imagen_cosecha($pool)) ?>" alt="<?= escapar($pool['producto'] . ' de ' . $pool['origen']) ?>" />
          <div class="productor-pool-body">
            <div class="productor-pool-top">
              <div>
                <span class="estado-chip"><?= escapar($pool['estado']) ?></span>
                <h3><?= escapar($pool['producto'] . ' - ' . $pool['variedad']) ?></h3>
                <p><?= escapar($pool['origen']) ?> - <?= escapar($pool['nodo_nombre'] ?? 'Nodo por confirmar') ?></p>
              </div>
              <strong class="precio-productor"><?= escapar(dinero($pool['precio_vigente'])) ?><small>/<?= escapar($pool['unidad']) ?></small></strong>
            </div>

            <div class="prog">
              <div class="prog-head">
                <span><?= escapar($pool['personas_actuales'] . '/' . $pool['personas_objetivo']) ?> personas</span>
                <span><?= escapar((string) $avance) ?>%</span>
              </div>
              <div class="bar"><i class="progress-fill <?= $avance >= 80 ? 'terra' : '' ?>" style="--pool-progress:<?= escapar((string) $avance) ?>%"></i></div>
            </div>

            <dl class="detalle-lista detalle-lista--compacta">
              <div><dt>Cierre</dt><dd><?= escapar(fecha_hora_corta($pool['fecha_cierre'])) ?></dd></div>
              <div><dt>Entrega</dt><dd><?= escapar(fecha_corta($pool['fecha_entrega'])) ?></dd></div>
              <div><dt>Compromisos</dt><dd><?= escapar((string) ($pool['compromisos_confirmados'] ?? 0)) ?></dd></div>
              <div><dt>Monto simulado</dt><dd><?= escapar(dinero($pool['monto_confirmado'] ?? 0)) ?></dd></div>
            </dl>

            <?php if (!empty($pool['siguiente_tramo'])): ?>
              <p class="nota-tramo">
                Faltan <?= escapar((string) $pool['faltan_siguiente_tramo']) ?> comprador(es) para <?= escapar(dinero($pool['siguiente_tramo']['precio_unitario'])) ?>/<?= escapar($pool['unidad']) ?>.
              </p>
            <?php else: ?>
              <p class="nota-tramo">Este pool ya usa el mejor precio configurado.</p>
            <?php endif; ?>

            <div class="productor-pool-actions">
              <a class="sbtn" href="<?= escapar(url_para('/pool.php?id=' . $pool['id'])) ?>">Ver pool publico</a>
              <?php if (($pool['estado'] ?? '') === 'activo'): ?>
                <form method="post" action="<?= escapar(url_para('/productor/retirar_pool.php')) ?>">
                  <?= campo_csrf() ?>
                  <input type="hidden" name="pool_id" value="<?= escapar($pool['id']) ?>" />
                  <button class="btn-outline btn-retirar" type="submit">Retirar publicacion</button>
                </form>
              <?php endif; ?>
            </div>
          </div>
        </article>
      <?php endforeach; ?>
    </section>
  <?php endif; ?>
</main>

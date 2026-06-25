<?php renderizar_vista('admin/nav.php'); ?>
<main class="admin-shell">
  <section class="admin-hero">
    <div>
      <p class="admin-kicker">Revision operativa</p>
      <h1>Pools publicados</h1>
      <p>Vista de control para precios por tramo, cierres, progreso y estado de pools.</p>
    </div>
  </section>

  <section class="admin-metricas" aria-label="Resumen de pools">
    <article>
      <span>Total</span>
      <strong><?= escapar((string) $metricas['total']) ?></strong>
    </article>
    <article>
      <span>Activos</span>
      <strong><?= escapar((string) $metricas['activos']) ?></strong>
    </article>
    <article>
      <span>Por cerrar</span>
      <strong><?= escapar((string) $metricas['por_cerrar']) ?></strong>
    </article>
    <article>
      <span>Comprometido</span>
      <strong><?= escapar(dinero($metricas['monto_confirmado'])) ?></strong>
    </article>
  </section>

  <nav class="admin-filtros" aria-label="Filtrar pools">
    <?php
      $filtros = [
          '' => 'Todos',
          'activo' => 'Activos',
          'cerrado' => 'Cerrados',
          'fallido' => 'Fallidos',
      ];
    ?>
    <?php foreach ($filtros as $valor => $texto): ?>
      <a class="<?= $estadoActual === $valor ? 'activo' : '' ?>" href="<?= escapar(url_para('/admin/pools.php' . ($valor !== '' ? '?estado=' . $valor : ''))) ?>">
        <?= escapar($texto) ?>
      </a>
    <?php endforeach; ?>
  </nav>

  <?php if (empty($pools)): ?>
    <section class="admin-empty">
      <strong>No hay pools para este filtro.</strong>
      <p>Cuando un productor publique un lote, aparecera aqui con precio, tramo y cierre.</p>
    </section>
  <?php else: ?>
    <section class="tabla-panel">
      <table class="tabla-surcos">
        <thead>
          <tr>
            <th>Pool</th>
            <th>Productor</th>
            <th>Precio vigente</th>
            <th>Estado</th>
            <th>Progreso</th>
            <th>Cierre</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($pools as $pool): ?>
            <?php $avance = $poolModelo->avance($pool); ?>
            <tr>
              <td data-label="Pool">
                <?= escapar($pool['producto'] . ' - ' . $pool['variedad']) ?><br>
                <small><?= escapar($pool['origen']) ?></small>
              </td>
              <td data-label="Productor"><?= escapar($pool['productor_nombre']) ?></td>
              <td data-label="Precio vigente">
                <?= escapar(dinero($pool['precio_vigente'])) ?>/<?= escapar($pool['unidad']) ?><br>
                <?php if (!empty($pool['siguiente_tramo'])): ?>
                  <small>Faltan <?= escapar((string) $pool['faltan_siguiente_tramo']) ?> para <?= escapar(dinero($pool['siguiente_tramo']['precio_unitario'])) ?></small>
                <?php else: ?>
                  <small>Mejor tramo activo</small>
                <?php endif; ?>
              </td>
              <td data-label="Estado"><span class="estado-chip"><?= escapar($pool['estado']) ?></span></td>
              <td data-label="Progreso">
                <?= escapar($pool['personas_actuales'] . '/' . $pool['personas_objetivo']) ?>
                <div class="mini-bar"><i style="width:<?= escapar((string) $avance) ?>%"></i></div>
              </td>
              <td data-label="Cierre"><?= escapar(fecha_hora_corta($pool['fecha_cierre'])) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </section>
  <?php endif; ?>
</main>

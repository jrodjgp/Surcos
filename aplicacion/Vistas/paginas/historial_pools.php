<main class="wrap wrap--md">
  <section class="terminal-head historial-head">
    <div>
      <p class="eyebrow">Historial de Pools</p>
      <h1>Actividad de compras</h1>
      <p class="sub">Este historial muestra compromisos confirmados y actividad comercial del comprador. La bitacora de solicitudes admin vive en otra seccion.</p>
    </div>
    <a class="btn-outline" href="<?= escapar(url_para('/bandeja.php')) ?>">Volver a Bandeja</a>
  </section>

  <section class="sec-head">
    <h2>Compromisos confirmados</h2>
    <span class="badge"><?= escapar((string) count($historial)) ?> registros</span>
  </section>

  <?php if (empty($historial)): ?>
    <div class="estado-vacio">
      <strong>No hay compromisos confirmados todavia.</strong>
      <p>Confirma un pool desde la bandeja para ver aqui monto, productor y estado.</p>
      <a href="<?= escapar(url_para('/')) ?>#pools-activos">Ver pools activos</a>
    </div>
  <?php else: ?>
    <section class="tabla-panel">
      <table class="tabla-surcos">
        <thead>
          <tr>
            <th>Pool</th>
            <th>Productor</th>
            <th>Monto</th>
            <th>Estado</th>
            <th>Entrega</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($historial as $registro): ?>
            <tr>
              <td data-label="Pool">
                <?= escapar($registro['producto_snapshot']) ?><br>
                <small><?= escapar($registro['cantidad'] . ' ' . $registro['unidad'] . ' - ' . $registro['origen_snapshot']) ?></small>
              </td>
              <td data-label="Productor">
                <a class="pool-historia-link" href="<?= escapar(url_para('/historias_productor.php?productor=' . $registro['productor_id'])) ?>"><?= escapar($registro['productor_nombre']) ?></a>
              </td>
              <td data-label="Monto"><?= escapar(dinero($registro['monto'])) ?></td>
              <td data-label="Estado"><?= escapar(str_replace('_', ' ', $registro['estado_grupo'])) ?></td>
              <td data-label="Entrega"><?= escapar(fecha_corta($registro['fecha_entrega'])) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </section>
  <?php endif; ?>

  <section class="sec-head">
    <h2>Actividad reciente</h2>
    <span class="badge"><?= escapar((string) count($actividad)) ?> eventos</span>
  </section>

  <?php if (empty($actividad)): ?>
    <div class="estado-vacio">
      <strong>No hay actividad registrada.</strong>
      <p>Cuando confirmes pools o cambien estados importantes, apareceran aqui.</p>
    </div>
  <?php else: ?>
    <div class="feed historial-feed">
      <?php foreach ($actividad as $item): ?>
        <article class="item">
          <span class="dt"><?= escapar(fecha_corta($item['fecha'])) ?></span>
          <p class="tx"><strong><?= escapar($item['tipo']) ?>:</strong> <?= escapar($item['texto']) ?></p>
        </article>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</main>

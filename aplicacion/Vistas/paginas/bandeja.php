<main class="wrap wrap--md">
  <section class="terminal-head">
    <div>
      <p class="eyebrow">Bandeja de Pools</p>
      <h1>Compromisos en borrador</h1>
    </div>
    <div class="terminal-actions">
      <a class="btn-outline" href="<?= escapar(url_para('/historial_pools.php')) ?>">Historial de Pools</a>
      <a class="btn-outline" href="<?= escapar(url_para('/')) ?>#pools-activos">Agregar mas pools</a>
    </div>
  </section>

  <?php if (empty($borradores)): ?>
    <div class="estado-vacio">
      <strong>No tienes borradores en la bandeja.</strong>
      <p>Agrega un pool activo para revisar monto, cierre y pago simulado antes de confirmar.</p>
    </div>
  <?php else: ?>
    <section class="tabla-panel">
      <table class="tabla-surcos">
        <thead>
          <tr>
            <th>Pool</th>
            <th>Cantidad</th>
            <th>Precio vigente</th>
            <th>Monto</th>
            <th>Cierre</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($borradores as $borrador): ?>
            <?php $montoVigente = round((float) $borrador['cantidad'] * (float) $borrador['precio_vigente'], 2); ?>
            <tr>
              <td data-label="Pool"><?= escapar($borrador['producto_snapshot']) ?><br><small><?= escapar($borrador['origen_snapshot']) ?></small></td>
              <td data-label="Cantidad"><?= escapar($borrador['cantidad'] . ' ' . $borrador['unidad']) ?></td>
              <td data-label="Precio vigente"><?= escapar(dinero($borrador['precio_vigente'])) ?>/<?= escapar($borrador['unidad']) ?></td>
              <td data-label="Monto"><?= escapar(dinero($montoVigente)) ?></td>
              <td data-label="Cierre"><?= escapar(fecha_hora_corta($borrador['fecha_cierre'])) ?></td>
              <td data-label="Accion">
                <form method="post" action="<?= escapar(url_para('/bandeja_quitar.php')) ?>">
                  <?= campo_csrf() ?>
                  <input type="hidden" name="compromiso_id" value="<?= escapar($borrador['id']) ?>" />
                  <button class="btn-outline" type="submit">Quitar</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </section>

    <form class="confirmar-panel" method="post" action="<?= escapar(url_para('/bandeja_confirmar.php')) ?>">
      <?= campo_csrf() ?>
      <div>
        <span>Total simulado</span>
        <strong><?= escapar(dinero($total)) ?></strong>
        <small>El procedimiento almacenado recalcula el precio vigente antes de autorizar.</small>
      </div>
      <label for="metodoPago">metodo de pago simulado</label>
      <select id="metodoPago" name="metodo_pago_id" required>
        <?php foreach ($metodosPago as $metodo): ?>
          <option value="<?= escapar($metodo['id']) ?>"><?= escapar($metodo['etiqueta']) ?></option>
        <?php endforeach; ?>
      </select>
      <button class="btn-primary" type="submit">Confirmar Bandeja</button>
    </form>
  <?php endif; ?>
</main>

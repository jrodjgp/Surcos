<?php renderizar_vista('admin/nav.php'); ?>
<main class="admin-shell">
  <section class="admin-hero">
    <div>
      <p class="admin-kicker">Bandeja admin</p>
      <h1>Solicitudes de afiliacion</h1>
    </div>
    <div class="admin-metricas">
      <?php foreach ($conteos as $estado => $total): ?>
        <a href="<?= escapar(url_para('/admin/solicitudes.php?estado=' . $estado)) ?>">
          <span><?= escapar(str_replace('_', ' ', $estado)) ?></span>
          <strong><?= escapar((string) $total) ?></strong>
        </a>
      <?php endforeach; ?>
    </div>
  </section>

  <section class="tabla-panel">
    <table class="tabla-surcos">
      <thead>
        <tr>
          <th>Nombre</th>
          <th>Tipo</th>
          <th>Estado</th>
          <th>Creada</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($solicitudes as $solicitud): ?>
          <tr>
            <td><?= escapar($solicitud['nombre']) ?><br><small><?= escapar($solicitud['correo']) ?></small></td>
            <td><?= escapar(str_replace('_', ' ', $solicitud['tipo_usuario'])) ?></td>
            <td><span class="estado-chip"><?= escapar(str_replace('_', ' ', $solicitud['estado'])) ?></span></td>
            <td><?= escapar(fecha_hora_corta($solicitud['creada_en'])) ?></td>
            <td><a class="btn-outline" href="<?= escapar(url_para('/admin/solicitud.php?id=' . $solicitud['id'])) ?>">Abrir</a></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </section>
</main>

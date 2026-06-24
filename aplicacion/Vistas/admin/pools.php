<?php renderizar_vista('admin/nav.php'); ?>
<main class="admin-shell">
  <section class="admin-hero">
    <div>
      <p class="admin-kicker">Revision operativa</p>
      <h1>Pools publicados</h1>
    </div>
  </section>

  <section class="tabla-panel">
    <table class="tabla-surcos">
      <thead>
        <tr>
          <th>Pool</th>
          <th>Productor</th>
          <th>Estado</th>
          <th>Progreso</th>
          <th>Cierre</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($pools as $pool): ?>
          <tr>
            <td><?= escapar($pool['producto'] . ' - ' . $pool['variedad']) ?><br><small><?= escapar($pool['origen']) ?></small></td>
            <td><?= escapar($pool['productor_nombre']) ?></td>
            <td><span class="estado-chip"><?= escapar($pool['estado']) ?></span></td>
            <td><?= escapar($pool['personas_actuales'] . '/' . $pool['personas_objetivo']) ?></td>
            <td><?= escapar(fecha_hora_corta($pool['fecha_cierre'])) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </section>
</main>

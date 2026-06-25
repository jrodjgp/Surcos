<?php renderizar_vista('admin/nav.php'); ?>
<main class="admin-shell admin-detalle">
  <section class="admin-hero">
    <div>
      <p class="admin-kicker"><?= escapar(str_replace('_', ' ', $solicitud['estado'])) ?></p>
      <h1><?= escapar($solicitud['nombre']) ?></h1>
      <p class="admin-contacto"><?= escapar($solicitud['correo']) ?> <span>/</span> <?= escapar($solicitud['telefono'] ?: 'sin telefono') ?></p>
    </div>
    <a class="btn-outline" href="<?= escapar(url_para('/admin/solicitudes.php')) ?>">Volver</a>
  </section>

  <section class="admin-grid">
    <article class="admin-panel">
      <h2>Solicitud</h2>
      <dl class="detalle-lista">
        <div><dt>Tipo</dt><dd><?= escapar(str_replace('_', ' ', $solicitud['tipo_usuario'])) ?></dd></div>
        <div><dt>Asunto</dt><dd><?= escapar($solicitud['asunto']) ?></dd></div>
        <div><dt>Mensaje</dt><dd><?= nl2br(escapar($solicitud['mensaje'])) ?></dd></div>
        <div><dt>Notas</dt><dd><?= nl2br(escapar($solicitud['notas_admin'] ?: 'Sin notas')) ?></dd></div>
      </dl>
    </article>

    <form class="admin-panel" method="post" action="<?= escapar(url_para('/admin/solicitud.php')) ?>">
      <?= campo_csrf() ?>
      <input type="hidden" name="id" value="<?= escapar($solicitud['id']) ?>" />
      <h2>Decision admin</h2>
      <label for="notaSolicitud">nota</label>
      <textarea id="notaSolicitud" name="nota" rows="6" placeholder="Motivo, validacion o proximo paso"></textarea>
      <div class="admin-acciones">
        <button class="btn-outline" name="accion" value="nota" type="submit">Guardar nota</button>
        <button class="btn-outline" name="accion" value="rechazar" type="submit">Rechazar</button>
        <button class="btn-primary" name="accion" value="aprobar" type="submit">Aprobar</button>
      </div>
    </form>
  </section>

  <section class="admin-panel">
    <h2>Bitacora de solicitud</h2>
    <p class="admin-ayuda">Estos registros pertenecen al proceso de revision administrativa. No son ventas ni historial de pools.</p>
    <?php if (empty($eventos)): ?>
      <div class="admin-vacio">
        <strong>Sin eventos registrados.</strong>
        <p>Cuando guardes una nota, apruebes o rechaces la solicitud, la bitacora aparecera aqui.</p>
      </div>
    <?php else: ?>
      <div class="bitacora-lista">
        <?php foreach ($eventos as $evento): ?>
          <div class="evento-admin">
            <div>
              <strong><?= escapar(str_replace('_', ' ', $evento['tipo'])) ?></strong>
              <span><?= escapar(fecha_hora_corta($evento['creado_en'])) ?><?= !empty($evento['admin_nombre']) ? ' / ' . escapar($evento['admin_nombre']) : '' ?></span>
            </div>
            <p><?= escapar($evento['detalle'] ?: 'Evento registrado sin detalle adicional.') ?></p>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </section>
</main>

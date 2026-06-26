<main class="admin-shell admin-shell--login">
  <section class="admin-login">
    <div>
      <p class="admin-kicker">Surcos Admin</p>
      <h1>Control de solicitudes</h1>
      <p>Panel para revisar afiliaciones y pools publicados.</p>
      <p class="demo-credenciales">Demo: admin@surcos.pa / Admin123!</p>
      <ol class="admin-ruta">
        <li>Revisa solicitudes nuevas.</li>
        <li>Aprueba una solicitud y copia la clave temporal mostrada una vez.</li>
        <li>Consulta pools publicados y cierra vencidos con accion manual.</li>
      </ol>
    </div>
    <form method="post" action="<?= escapar(url_para('/admin/')) ?>">
      <?= campo_csrf() ?>
      <label for="correoAdmin">correo</label>
      <input id="correoAdmin" name="correo" type="email" value="admin@surcos.pa" required />
      <label for="claveAdmin">clave</label>
      <input id="claveAdmin" name="clave" type="password" required />
      <button class="btn-primary" type="submit">Ingresar</button>
    </form>
  </section>
</main>

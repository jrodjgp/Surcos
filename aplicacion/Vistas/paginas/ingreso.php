<main class="wrap wrap--md">
  <section class="auth-shell">
    <div class="auth-copy">
      <p class="eyebrow">Cuenta Surcos</p>
      <h1>Ingresa a tu Bandeja de Pools</h1>
      <p>Usa el comprador demo para probar compromisos y confirmacion simulada.</p>
      <p class="demo-credenciales">Demo: comprador@surcos.pa / Surcos123!</p>
    </div>
    <form class="autenticacion-formulario" method="post" action="<?= escapar(url_para('/ingreso.php')) ?>">
      <?= campo_csrf() ?>
      <label for="correoIngreso">correo</label>
      <input class="form-control" id="correoIngreso" name="correo" type="email" value="comprador@surcos.pa" required />
      <label for="claveIngreso">clave</label>
      <input class="form-control" id="claveIngreso" name="clave" type="password" required />
      <button class="btn-primary" type="submit">Ingresar</button>
      <a class="btn-outline" href="<?= escapar(url_para('/contacto.php')) ?>">Solicitar acceso</a>
    </form>
  </section>
</main>

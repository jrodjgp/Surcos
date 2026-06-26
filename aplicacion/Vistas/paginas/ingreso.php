<main class="wrap wrap--md">
  <section class="auth-shell">
    <div class="auth-copy">
      <p class="eyebrow">Cuenta Surcos</p>
      <h1>Ingresa a tu Bandeja de Pools</h1>
      <p>Usa el comprador demo para probar bandeja, pago simulado e historial. Usa el productor demo para publicar una cosecha desde el registro.</p>
      <div class="demo-credenciales demo-credenciales--lista" aria-label="Credenciales demo">
        <span>Comprador: comprador@surcos.pa / Surcos123!</span>
        <span>Productor: productor@surcos.pa / Surcos123!</span>
        <span>Empresa: empresa@surcos.pa / Surcos123!</span>
        <a href="<?= escapar(url_para('/admin/')) ?>">Admin: admin@surcos.pa / Admin123!</a>
      </div>
      <ol class="ruta-demo">
        <li>Abre un pool activo.</li>
        <li>Agrega la cantidad minima a la bandeja.</li>
        <li>Confirma con pago simulado y revisa historial.</li>
      </ol>
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

<main class="wrap wrap--md">
  <section class="auth-shell">
    <div class="auth-copy">
      <p class="eyebrow">Activacion requerida</p>
      <h1>Cambia tu clave temporal</h1>
      <p>Hola, <?= escapar($usuario['nombre'] ?? 'usuario') ?>. Para usar Surcos debes reemplazar la clave temporal por una clave propia.</p>
    </div>
    <form class="autenticacion-formulario" method="post" action="<?= escapar(url_para('/activar_cuenta.php')) ?>">
      <?= campo_csrf() ?>
      <label for="claveActual">clave temporal</label>
      <input class="form-control" id="claveActual" name="clave_actual" type="password" autocomplete="current-password" required />

      <label for="claveNueva">nueva clave</label>
      <input class="form-control" id="claveNueva" name="clave_nueva" type="password" minlength="8" autocomplete="new-password" required />

      <label for="claveConfirmacion">confirmar nueva clave</label>
      <input class="form-control" id="claveConfirmacion" name="clave_confirmacion" type="password" minlength="8" autocomplete="new-password" required />

      <button class="btn-primary" type="submit">Activar cuenta</button>
      <a class="btn-outline" href="<?= escapar(url_para('/salir.php')) ?>">Salir</a>
    </form>
  </section>
</main>

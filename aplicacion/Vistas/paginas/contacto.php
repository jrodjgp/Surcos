<main class="contacto-shell">
  <section class="contacto-hero">
    <div>
      <p class="eyebrow">Solicitud de afiliacion</p>
      <h1>Entra a la red de Surcos</h1>
      <p>Compradores, productores, empresas y aliados logisticos pueden solicitar acceso. El equipo admin revisa cada solicitud antes de activar una cuenta.</p>
    </div>
    <aside class="contacto-meta" aria-label="estado de respuesta">
      <span>Tiempo estimado</span>
      <strong>24-48 h</strong>
      <span>Revision manual</span>
    </aside>
  </section>

  <section class="contacto-grid">
    <form class="contacto-formulario needs-validation" method="post" action="<?= escapar(url_para('/contacto.php')) ?>">
      <?= campo_csrf() ?>
      <h2>Formulario de contacto</h2>
      <div class="contacto-campos">
        <div class="contacto-campo">
          <label for="nombreContacto">nombre</label>
          <input id="nombreContacto" name="nombre" type="text" minlength="3" required />
        </div>
        <div class="contacto-campo">
          <label for="correoContacto">correo</label>
          <input id="correoContacto" name="correo" type="email" required />
        </div>
        <div class="contacto-campo">
          <label for="telefonoContacto">telefono</label>
          <input id="telefonoContacto" name="telefono" type="tel" />
        </div>
        <div class="contacto-campo">
          <label for="tipoUsuario">tipo de afiliacion</label>
          <select id="tipoUsuario" name="tipo_usuario" required>
            <option value="">Selecciona una opcion</option>
            <option value="comprador">Comprador</option>
            <option value="productor">Productor</option>
            <option value="empresa">Empresa</option>
            <option value="aliado_logistico">Aliado logistico</option>
          </select>
        </div>
        <div class="contacto-campo contacto-campo--full">
          <label for="asuntoContacto">asunto</label>
          <input id="asuntoContacto" name="asunto" type="text" minlength="4" required />
        </div>
        <div class="contacto-campo contacto-campo--full">
          <label for="mensajeContacto">mensaje</label>
          <textarea id="mensajeContacto" name="mensaje" rows="6" minlength="10" required></textarea>
        </div>
      </div>
      <div class="contacto-check">
        <input id="aceptaContacto" name="acepta_contacto" type="checkbox" value="1" required />
        <label for="aceptaContacto">Autorizo ser contactado para revisar esta solicitud.</label>
      </div>
      <button class="btn-primary" type="submit">Enviar solicitud</button>
    </form>

    <aside class="contacto-aside">
      <div class="contacto-insignia">SR</div>
      <h2>Que revisa admin</h2>
      <div class="contacto-lista">
        <article>
          <h3>Identidad</h3>
          <p>Nombre, correo, telefono y tipo de afiliacion.</p>
        </article>
        <article>
          <h3>Uso esperado</h3>
          <p>Comprar en pools, publicar cosechas o apoyar logistica.</p>
        </article>
        <article>
          <h3>Activacion</h3>
          <p>La aprobacion crea una cuenta pendiente con clave temporal.</p>
        </article>
      </div>
    </aside>
  </section>
</main>

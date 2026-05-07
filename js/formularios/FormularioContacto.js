class FormularioContacto {
  constructor(formulario) {
    this.formulario = formulario;
    this.mensaje = document.getElementById('mensajeContacto');
    this.insignia = document.getElementById('insigniaContacto');
    this.boton = formulario.querySelector('[data-accion="enviar-contacto"]');
  }

  iniciar() {
    this.hidratarUsuario();
    this.formulario.addEventListener('submit', (evento) => this.manejarEnvio(evento));
    this.formulario.addEventListener('input', () => this.actualizarEstadoBoton());
    this.actualizarEstadoBoton();
  }

  hidratarUsuario() {
    const usuario = window.AutenticacionSurcos?.obtenerUsuarioActual?.();

    if (!usuario) {
      return;
    }

    this.escribirCampo('nombre', usuario.nombre);
    this.escribirCampo('correo', usuario.correo);
    this.escribirCampo('telefono', usuario.telefono || '');
  }

  escribirCampo(nombre, valor) {
    const campo = this.formulario.elements[nombre];

    if (campo && !campo.value) {
      campo.value = valor;
    }
  }

  manejarEnvio(evento) {
    evento.preventDefault();
    evento.stopPropagation();

    this.formulario.classList.add('was-validated');

    if (!this.formulario.checkValidity()) {
      this.mostrarMensaje('Revisa los campos marcados para enviar tu mensaje.', false);
      return;
    }

    const contacto = this.crearContacto();
    const registro = this.guardarContacto(contacto);
    this.mostrarMensaje(contacto.crearMensajeConfirmacion(), true, registro);
    this.actualizarInsignia(contacto);
    this.formulario.reset();
    this.formulario.classList.remove('was-validated');
    this.actualizarEstadoBoton();
  }

  crearContacto() {
    const datos = new FormData(this.formulario);

    return new window.Contacto({
      nombre: datos.get('nombre'),
      correo: datos.get('correo'),
      telefono: datos.get('telefono') || 'No indicado',
      tipoUsuario: datos.get('tipoUsuario'),
      asunto: datos.get('asunto'),
      mensaje: datos.get('mensaje')
    });
  }

  guardarContacto(contacto) {
    if (!window.EstadoSurcos || !window.FormatoSurcos) {
      return contacto.crearRegistro();
    }

    return window.EstadoSurcos.agregar('mensajesContacto', contacto.crearRegistro());
  }

  actualizarEstadoBoton() {
    this.boton.disabled = !this.formulario.checkValidity();
  }

  actualizarInsignia(contacto) {
    if (!this.insignia) {
      return;
    }

    this.insignia.textContent = contacto.iniciales || 'SR';
  }

  mostrarMensaje(texto, esExito, registro = null) {
    this.mensaje.textContent = texto;
    this.mensaje.hidden = false;
    this.mensaje.classList.toggle('alert-success', esExito);
    this.mensaje.classList.toggle('alert-danger', !esExito);

    if (registro && esExito) {
      const acciones = document.createElement('div');
      acciones.className = 'contacto-acciones';
      acciones.innerHTML = `
        <a href="mi_terminal_dashboard.html">Ir a Mi Terminal</a>
        <a href="marketplace_terminal.html">Volver al Marketplace</a>
      `;
      this.mensaje.appendChild(acciones);
    }
  }
}

window.FormularioContacto = FormularioContacto;


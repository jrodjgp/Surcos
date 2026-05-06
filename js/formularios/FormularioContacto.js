class FormularioContacto {
  constructor(formulario) {
    this.formulario = formulario;
    this.mensaje = document.getElementById('mensajeContacto');
    this.insignia = document.getElementById('insigniaContacto');
    this.boton = formulario.querySelector('[data-accion="enviar-contacto"]');
  }

  iniciar() {
    this.formulario.addEventListener('submit', (evento) => this.manejarEnvio(evento));
    this.formulario.addEventListener('input', () => this.actualizarEstadoBoton());
    this.actualizarEstadoBoton();
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
    this.mostrarMensaje(contacto.crearMensajeConfirmacion(), true);
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

  actualizarEstadoBoton() {
    this.boton.disabled = !this.formulario.checkValidity();
  }

  actualizarInsignia(contacto) {
    if (!this.insignia) {
      return;
    }

    this.insignia.textContent = contacto.iniciales || 'SR';
  }

  mostrarMensaje(texto, esExito) {
    this.mensaje.textContent = texto;
    this.mensaje.hidden = false;
    this.mensaje.classList.toggle('alert-success', esExito);
    this.mensaje.classList.toggle('alert-danger', !esExito);
  }
}

window.FormularioContacto = FormularioContacto;


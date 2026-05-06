class FormularioIngreso {
  constructor(selector) {
    this.formulario = document.querySelector(selector);
    this.mensaje = document.querySelector('[data-mensaje-ingreso]');
  }

  iniciar() {
    if (!this.formulario) {
      return;
    }

    this.conservarRetorno();
    this.formulario.addEventListener('submit', (evento) => this.enviar(evento));
  }

  enviar(evento) {
    evento.preventDefault();

    if (!this.formulario.checkValidity()) {
      this.formulario.classList.add('was-validated');
      this.mostrarMensaje('Completa el correo y la clave para ingresar.');
      return;
    }

    const datos = new FormData(this.formulario);
    const usuario = window.AutenticacionSurcos.validarCredenciales(datos.get('correo'), datos.get('clave'));

    if (!usuario) {
      this.mostrarMensaje('Correo o clave incorrectos. Puedes usar la cuenta demo.');
      return;
    }

    window.AutenticacionSurcos.iniciarSesion(usuario);
    this.mostrarMensaje('Ingreso confirmado. Abriendo tu terminal.');

    window.setTimeout(() => {
      window.location.href = this.obtenerRetorno();
    }, 650);
  }

  obtenerRetorno() {
    const parametros = new URLSearchParams(window.location.search);
    return parametros.get('retorno') || 'mi_terminal_dashboard.html';
  }

  conservarRetorno() {
    const parametros = new URLSearchParams(window.location.search);
    const retorno = parametros.get('retorno');

    if (!retorno) {
      return;
    }

    document.querySelectorAll('a[href="registro.html"]').forEach((enlace) => {
      enlace.href = `registro.html?retorno=${encodeURIComponent(retorno)}`;
    });
  }

  mostrarMensaje(texto) {
    if (this.mensaje) {
      this.mensaje.textContent = texto;
    }
  }
}

new FormularioIngreso('#formularioIngreso').iniciar();

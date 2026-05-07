class FormularioIngreso {
  constructor(selector) {
    this.formulario = document.querySelector(selector);
    this.mensaje = document.querySelector('[data-mensaje-ingreso]');
    this.correo = document.querySelector('#correoIngreso');
    this.clave = document.querySelector('#claveIngreso');
    this.vistaCorreo = document.querySelector('[data-vista-correo]');
    this.vistaIniciales = document.querySelector('[data-vista-iniciales]');
    this.vistaEstado = document.querySelector('[data-vista-estado]');
    this.botonPrueba = document.querySelector('[data-prueba-ingreso]');
  }

  iniciar() {
    if (!this.formulario) {
      return;
    }

    this.conservarRetorno();
    this.actualizarVista();
    this.formulario.addEventListener('submit', (evento) => this.enviar(evento));
    this.correo?.addEventListener('input', () => this.actualizarVista());
    this.clave?.addEventListener('input', () => this.actualizarVista());
    this.botonPrueba?.addEventListener('click', () => this.usarPrueba());
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
      this.mostrarMensaje('Correo o clave incorrectos. Puedes usar la cuenta de prueba.');
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

  usarPrueba() {
    if (this.correo) {
      this.correo.value = 'nodo@surcos.pa';
    }

    if (this.clave) {
      this.clave.value = 'surcos2026';
    }

    this.actualizarVista();
    this.mostrarMensaje('Cuenta de prueba cargada. Puedes ingresar al terminal.');
  }

  actualizarVista() {
    const correo = String(this.correo?.value || 'nodo@surcos.pa').trim();
    const tieneClave = Boolean(String(this.clave?.value || '').trim());

    if (this.vistaCorreo) {
      this.vistaCorreo.textContent = correo || 'nodo@surcos.pa';
    }

    if (this.vistaIniciales) {
      this.vistaIniciales.textContent = this.crearInicialesCorreo(correo);
    }

    if (this.vistaEstado) {
      this.vistaEstado.textContent = tieneClave ? 'Credencial lista' : 'Esperando clave';
    }
  }

  crearInicialesCorreo(correo) {
    const nombre = String(correo || 'nodo surcos')
      .split('@')[0]
      .replace(/[._-]+/g, ' ');

    return window.AutenticacionSurcos.crearIniciales(nombre);
  }
}

new FormularioIngreso('#formularioIngreso').iniciar();

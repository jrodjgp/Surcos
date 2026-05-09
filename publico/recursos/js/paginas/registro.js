class FormularioRegistro {
  constructor(selector) {
    this.formulario = document.querySelector(selector);
    this.mensaje = document.querySelector('[data-mensaje-registro]');
    this.campos = {
      nombre: document.querySelector('#nombreRegistro'),
      correo: document.querySelector('#correoRegistro'),
      provincia: document.querySelector('#provinciaRegistro'),
      rol: document.querySelector('#rolRegistro'),
      nodo: document.querySelector('#nodoRegistro')
    };
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
      this.mostrarMensaje('Revisa los campos marcados antes de crear la cuenta.');
      return;
    }

    const datos = new FormData(this.formulario);
    const clave = datos.get('clave');
    const confirmacion = datos.get('confirmacionClave');

    if (clave !== confirmacion) {
      this.mostrarMensaje('Las claves no coinciden.');
      return;
    }

    const resultado = window.AutenticacionSurcos.registrarUsuario({
      nombre: datos.get('nombre'),
      correo: datos.get('correo'),
      telefono: datos.get('telefono'),
      provincia: datos.get('provincia'),
      rol: datos.get('rol'),
      clave,
      nodoRetiro: datos.get('nodoRetiro')
    });

    if (!resultado.exito) {
      this.mostrarMensaje(resultado.mensaje);
      return;
    }

    this.mostrarMensaje('Cuenta creada. Abriendo tu terminal.');

    window.setTimeout(() => {
      window.location.href = this.obtenerRetorno();
    }, 700);
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

    document.querySelectorAll('a[href="ingreso.html"]').forEach((enlace) => {
      enlace.href = `ingreso.html?retorno=${encodeURIComponent(retorno)}`;
    });
  }

  mostrarMensaje(texto) {
    if (this.mensaje) {
      this.mensaje.textContent = texto;
    }
  }
}

new FormularioRegistro('#formularioRegistro').iniciar();

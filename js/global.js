class SesionInterfaz {
  constructor() {
    this.autenticacion = window.AutenticacionSurcos;
  }

  iniciar() {
    if (!this.autenticacion) {
      return;
    }

    this.usuario = this.autenticacion.obtenerUsuarioActual();
    this.actualizarAccesoPerfil();
    this.actualizarDatosUsuario();
    this.registrarCierreSesion();
  }

  actualizarAccesoPerfil() {
    document.querySelectorAll('.nav-user').forEach((enlace) => {
      enlace.href = this.usuario ? 'perfil.html' : 'ingreso.html';
      enlace.setAttribute('aria-label', this.usuario ? `perfil de ${this.usuario.nombre}` : 'ingresar a Surcos');
      enlace.classList.toggle('sesion-activa', Boolean(this.usuario));
    });
  }

  actualizarDatosUsuario() {
    if (!this.usuario) {
      return;
    }

    this.escribir('[data-usuario-nombre]', this.usuario.nombre);
    this.escribir('[data-usuario-correo]', this.usuario.correo);
    this.escribir('[data-usuario-iniciales]', this.usuario.iniciales || this.autenticacion.crearIniciales(this.usuario.nombre));
    this.escribir('[data-usuario-provincia]', `NODO CONSUMIDOR - ${this.usuario.provincia}`);
    this.escribir('[data-usuario-nodo]', this.usuario.nodoRetiro);
  }

  escribir(selector, texto) {
    document.querySelectorAll(selector).forEach((elemento) => {
      elemento.textContent = texto;
    });
  }

  registrarCierreSesion() {
    document.querySelectorAll('[data-accion-sesion="cerrar"]').forEach((control) => {
      control.addEventListener('click', (evento) => {
        evento.preventDefault();
        evento.stopPropagation();
        this.autenticacion.cerrarSesion();
        new AccionesDemostracion().mostrarAviso('Sesion cerrada. Volviendo al ingreso.');
        window.setTimeout(() => {
          window.location.href = 'ingreso.html';
        }, 900);
      });
    });
  }
}

class AccionesDemostracion {
  constructor() {
    this.aviso = null;
    this.temporizador = null;
  }

  iniciar() {
    document.addEventListener('click', (evento) => this.manejarClic(evento));
  }

  manejarClic(evento) {
    const objetivo = evento.target.closest('a[href="#"], button');

    if (!objetivo || this.esControlReal(objetivo)) {
      return;
    }

    evento.preventDefault();
    this.mostrarAviso(this.crearMensaje(objetivo));
  }

  esControlReal(objetivo) {
    const tipo = objetivo.getAttribute('type');
    const esEnvio = objetivo.tagName === 'BUTTON' && tipo === 'submit';
    const esCajon = objetivo.classList.contains('drawer-trigger') || objetivo.classList.contains('x');
    const esSesion = objetivo.dataset.accionSesion;
    const esGrupo = objetivo.dataset.accionGrupo;
    const esTerminal = objetivo.dataset.accionTerminal;
    const esFiltroHistorial = objetivo.dataset.filtroHistorial;

    return esEnvio || esCajon || esSesion || esGrupo || esTerminal || esFiltroHistorial || objetivo.disabled;
  }

  crearMensaje(objetivo) {
    const texto = objetivo.dataset.mensajeDemostracion || objetivo.textContent.trim().replace(/\s+/g, ' ');
    return texto ? `Demo del terminal: ${texto}` : 'Demo del terminal: accion registrada';
  }

  mostrarAviso(mensaje) {
    if (!this.aviso) {
      this.aviso = document.createElement('div');
      this.aviso.className = 'aviso-demostracion';
      this.aviso.setAttribute('role', 'status');
      this.aviso.setAttribute('aria-live', 'polite');
      document.body.appendChild(this.aviso);
    }

    this.aviso.textContent = mensaje;
    this.aviso.classList.add('visible');
    window.clearTimeout(this.temporizador);
    this.temporizador = window.setTimeout(() => {
      this.aviso.classList.remove('visible');
    }, 2600);
  }
}

if (window.CajonLateral) {
  new window.CajonLateral().iniciar();
}

new SesionInterfaz().iniciar();
new AccionesDemostracion().iniciar();

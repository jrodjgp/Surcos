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
        this.usuario = this.autenticacion.iniciarSesionDemo();
        this.actualizarAccesoPerfil();
        this.actualizarDatosUsuario();
        new AccionesDemostracion().mostrarAviso('Modo demo activo: sesion restaurada para continuar el recorrido.');
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
    const esPago = objetivo.dataset.accionPago;
    const esPerfil = objetivo.dataset.accionPerfil;
    const esConfiguracion = objetivo.dataset.accionConfiguracion;
    const esOrden = objetivo.dataset.accionOrden;
    const esHistorialOrden = objetivo.dataset.accionHistorialOrden;

    return esEnvio || esCajon || esSesion || esGrupo || esTerminal || esFiltroHistorial || esPago || esPerfil || esConfiguracion || esOrden || esHistorialOrden || objetivo.disabled;
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

class CintaTerminal {
  iniciar() {
    if (!window.EstadoSurcos) {
      return;
    }

    document.querySelectorAll('.ticker-track').forEach((pista) => {
      this.actualizarPista(pista);
    });
  }

  actualizarPista(pista) {
    const fragmento = document.createDocumentFragment();
    const elementos = this.obtenerElementos();

    [...elementos, ...elementos].forEach((elemento, indice) => {
      fragmento.appendChild(this.crearElemento(elemento));

      if (indice < elementos.length * 2 - 1) {
        fragmento.appendChild(this.crearSeparador());
      }
    });

    pista.replaceChildren(fragmento);
  }

  obtenerElementos() {
    const grupos = window.EstadoSurcos.obtenerColeccion('gruposCompra')
      .filter((grupo) => grupo.estado === 'activo');
    const ordenes = window.EstadoSurcos.obtenerColeccion('ordenes');
    const entregas = ordenes.filter((orden) => orden.estadoEntrega === 'entregado').length;
    const provincias = new Set(grupos.map((grupo) => this.obtenerProvincia(grupo)).filter(Boolean));
    const asegurados = grupos.filter((grupo) => Number(grupo.personasActuales || 0) >= Number(grupo.personasObjetivo || 1)).length;
    const siguiente = this.obtenerSiguienteCierre(grupos);

    return [
      { etiqueta: 'Pools Activos', valor: grupos.length },
      { etiqueta: 'Entregas Recientes', valor: entregas },
      { etiqueta: 'Pools Asegurados', valor: asegurados },
      { etiqueta: 'Provincias Activas', valor: provincias.size },
      { texto: siguiente ? `Proximo Cierre: ${siguiente}` : 'Proximo Cierre: sin pools activos' }
    ];
  }

  crearElemento(elemento) {
    const elementoCinta = document.createElement('span');
    elementoCinta.className = 'ticker-item';

    if (elemento.texto) {
      elementoCinta.textContent = elemento.texto;
      return elementoCinta;
    }

    elementoCinta.append(`${elemento.etiqueta}: `);
    const valor = document.createElement('b');
    valor.textContent = elemento.valor;
    elementoCinta.appendChild(valor);
    return elementoCinta;
  }

  crearSeparador() {
    const separador = document.createElement('span');
    separador.className = 'ticker-item ticker-separador';
    separador.textContent = '//';
    return separador;
  }

  obtenerProvincia(grupo) {
    return String(grupo.origen || '').split(',').pop().trim();
  }

  obtenerSiguienteCierre(grupos) {
    const grupo = [...grupos]
      .sort((primero, segundo) => new Date(primero.fechaCierre) - new Date(segundo.fechaCierre))[0];

    if (!grupo) {
      return '';
    }

    const fecha = new Date(grupo.fechaCierre);
    const dia = fecha.toLocaleDateString('es-PA', { day: '2-digit', month: 'short' });
    return `${grupo.producto} ${dia}`;
  }
}

if (window.CajonLateral) {
  new window.CajonLateral().iniciar();
}

new CintaTerminal().iniciar();
new SesionInterfaz().iniciar();
new AccionesDemostracion().iniciar();

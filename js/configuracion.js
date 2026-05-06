class ConfiguracionTerminal {
  constructor() {
    this.campoNombre = document.querySelector('[data-configuracion="nombre"]');
    this.campoCorreo = document.querySelector('[data-configuracion="correo"]');
    this.selectorIdioma = document.querySelector('[data-configuracion="idioma"]');
    this.selectorProvincia = document.querySelector('[data-configuracion="provinciaRetiro"]');
    this.selectorNodo = document.querySelector('[data-configuracion="nodoRetiro"]');
    this.controlesNotificacion = document.querySelectorAll('[data-notificacion]');
    this.rangoUmbral = document.getElementById('umbralCompromiso');
    this.textoUmbral = document.getElementById('valorUmbral');
    this.radiosUnidad = document.querySelectorAll('input[name="unidadDatos"]');
    this.valorVista = document.getElementById('vistaVolumen');
    this.notaVista = document.getElementById('notaUnidad');
    this.mensaje = document.querySelector('[data-mensaje-configuracion]');
    this.botonesAccion = document.querySelectorAll('[data-accion-configuracion]');
  }

  iniciar() {
    if (!window.EstadoSurcos || !window.AutenticacionSurcos) {
      return;
    }

    this.estado = window.EstadoSurcos.obtenerEstado();
    this.usuario = window.AutenticacionSurcos.obtenerUsuarioActual() || this.estado.usuarioDemo;
    this.hidratarFormulario();
    this.registrarEventos();
  }

  hidratarFormulario() {
    const configuracion = this.obtenerConfiguracion();

    this.escribirValor(this.campoNombre, this.usuario?.nombre || 'Juan Juanes');
    this.escribirValor(this.campoCorreo, this.usuario?.correo || 'nodo@surcos.pa');
    this.escribirValor(this.selectorIdioma, configuracion.idioma || 'ES');

    const provincia = configuracion.provinciaRetiro || this.usuario?.provincia || 'Panama';
    const nodo = configuracion.nodoRetiro || this.usuario?.nodoRetiro || '';
    this.poblarProvincias(provincia);
    this.poblarNodos(this.selectorProvincia?.value || provincia, nodo);
    this.hidratarNotificaciones(configuracion.notificaciones || {});
    this.hidratarUmbral(configuracion.umbralCompromiso || 60);
    this.hidratarUnidad(configuracion.unidadDatos || 'metrico');
  }

  registrarEventos() {
    this.rangoUmbral?.addEventListener('input', () => this.actualizarUmbral());
    this.radiosUnidad.forEach((radio) => {
      radio.addEventListener('change', () => this.actualizarUnidad());
    });

    this.selectorProvincia?.addEventListener('change', () => {
      this.poblarNodos(this.selectorProvincia.value);
    });

    this.botonesAccion.forEach((boton) => {
      boton.addEventListener('click', () => this.manejarAccion(boton.dataset.accionConfiguracion));
    });
  }

  obtenerConfiguracion() {
    const semilla = window.DatosInicialesSurcos?.configuracion || {};
    const guardado = this.estado.configuracion || {};

    return {
      ...semilla,
      ...guardado,
      notificaciones: {
        ...(semilla.notificaciones || {}),
        ...(guardado.notificaciones || {})
      }
    };
  }

  escribirValor(control, valor) {
    if (control) {
      control.value = valor;
    }
  }

  poblarProvincias(provinciaActiva) {
    if (!this.selectorProvincia) {
      return;
    }

    const nodos = this.obtenerNodosRetiro();
    const provincias = [...new Set(nodos.map((registro) => registro.provincia))];

    this.selectorProvincia.innerHTML = provincias.map((provincia) => (
      `<option value="${provincia}">${provincia}</option>`
    )).join('');

    this.selectorProvincia.value = provincias.includes(provinciaActiva) ? provinciaActiva : provincias[0];
  }

  poblarNodos(provinciaActiva, nodoActivo = '') {
    if (!this.selectorNodo) {
      return;
    }

    const nodos = this.obtenerNodosRetiro()
      .filter((registro) => registro.provincia === provinciaActiva);

    this.selectorNodo.innerHTML = nodos.map((registro) => (
      `<option value="${registro.nodo}">${registro.nodo}</option>`
    )).join('');

    const nombres = nodos.map((registro) => registro.nodo);
    this.selectorNodo.value = nombres.includes(nodoActivo) ? nodoActivo : nombres[0] || '';
  }

  obtenerNodosRetiro() {
    const nodos = this.estado.nodosRetiro || window.EstadoSurcos.obtenerColeccion('nodosRetiro');
    return nodos.length ? nodos : [{ provincia: 'Panama', nodo: 'PTY Terminal Oeste' }];
  }

  hidratarNotificaciones(notificaciones) {
    this.controlesNotificacion.forEach((control) => {
      const nombre = control.dataset.notificacion;
      control.checked = Boolean(notificaciones[nombre]);
    });
  }

  hidratarUmbral(valor) {
    if (this.rangoUmbral) {
      this.rangoUmbral.value = valor;
      this.actualizarUmbral();
    }
  }

  hidratarUnidad(unidad) {
    this.radiosUnidad.forEach((radio) => {
      radio.checked = radio.value === unidad;
    });
    this.actualizarUnidad();
  }

  actualizarUmbral() {
    if (!this.rangoUmbral || !this.textoUmbral) {
      return;
    }

    const valor = Number(this.rangoUmbral.value);
    this.textoUmbral.textContent = `${valor}%`;
    this.rangoUmbral.style.setProperty('--valor-rango', `${valor}%`);
  }

  actualizarUnidad() {
    const unidadActiva = document.querySelector('input[name="unidadDatos"]:checked')?.value || 'metrico';
    const esImperial = unidadActiva === 'imperial';
    const volumenKg = 1240;
    const volumen = esImperial
      ? Math.round(volumenKg * 2.20462).toLocaleString('es-PA')
      : volumenKg.toLocaleString('es-PA');
    const unidad = esImperial ? 'lb' : 'kg';

    if (this.valorVista) {
      this.valorVista.textContent = `${volumen} ${unidad}`;
    }

    if (this.notaVista) {
      this.notaVista.textContent = esImperial
        ? 'Los volumenes del terminal se mostraran en libras.'
        : 'Los volumenes del terminal se mostraran en kilogramos.';
    }
  }

  manejarAccion(accion) {
    const acciones = {
      guardar: () => this.guardarAjustes(),
      exportar: () => this.exportarDatos(),
      clave: () => this.mostrarMensaje('Cambio de contrasena preparado para la siguiente version con servidor.'),
      regenerar: () => this.mostrarMensaje('Identificador regenerado en modo demostracion. El acceso local se mantiene activo.'),
      eliminar: () => this.mostrarMensaje('Eliminacion simulada. No se borra la cuenta durante la demostracion.')
    };

    acciones[accion]?.();
  }

  guardarAjustes() {
    const nombre = this.campoNombre?.value.trim() || 'Juan Juanes';
    const correo = this.campoCorreo?.value.trim() || 'nodo@surcos.pa';
    const provinciaRetiro = this.selectorProvincia?.value || 'Panama';
    const nodoRetiro = this.selectorNodo?.value || 'PTY Terminal Oeste';
    const usuarioActualizado = {
      ...this.usuario,
      nombre,
      correo,
      provincia: provinciaRetiro,
      nodoRetiro,
      iniciales: window.AutenticacionSurcos.crearIniciales(nombre)
    };

    const configuracion = {
      ...this.obtenerConfiguracion(),
      idioma: this.selectorIdioma?.value || 'ES',
      unidadDatos: document.querySelector('input[name="unidadDatos"]:checked')?.value || 'metrico',
      umbralCompromiso: Number(this.rangoUmbral?.value || 60),
      provinciaRetiro,
      nodoRetiro,
      notificaciones: this.obtenerNotificaciones()
    };

    this.estado = {
      ...this.estado,
      configuracion,
      usuarios: this.actualizarUsuario(usuarioActualizado),
      usuarioDemo: this.usuario?.id === this.estado.usuarioDemo?.id ? usuarioActualizado : this.estado.usuarioDemo
    };

    window.EstadoSurcos.guardarEstado(this.estado);
    this.usuario = usuarioActualizado;
    this.mostrarMensaje('Ajustes guardados. Perfil, nodo y preferencias quedaron sincronizados.');
  }

  actualizarUsuario(usuarioActualizado) {
    const usuarios = this.estado.usuarios || [];
    const existe = usuarios.some((usuario) => usuario.id === usuarioActualizado.id);

    if (!existe) {
      return [...usuarios, usuarioActualizado];
    }

    return usuarios.map((usuario) => (
      usuario.id === usuarioActualizado.id ? { ...usuario, ...usuarioActualizado } : usuario
    ));
  }

  obtenerNotificaciones() {
    return Array.from(this.controlesNotificacion).reduce((notificaciones, control) => ({
      ...notificaciones,
      [control.dataset.notificacion]: control.checked
    }), {});
  }

  exportarDatos() {
    const contenido = JSON.stringify(window.EstadoSurcos.obtenerEstado(), null, 2);
    const archivo = new Blob([contenido], { type: 'application/json' });
    const enlace = document.createElement('a');
    enlace.href = URL.createObjectURL(archivo);
    enlace.download = 'datos-surcos-terminal.json';
    enlace.click();
    URL.revokeObjectURL(enlace.href);
    this.mostrarMensaje('Datos del terminal exportados en formato JSON.');
  }

  mostrarMensaje(texto) {
    if (!this.mensaje) {
      return;
    }

    this.mensaje.textContent = texto;
    this.mensaje.hidden = false;
    window.clearTimeout(this.temporizadorMensaje);
    this.temporizadorMensaje = window.setTimeout(() => {
      this.mensaje.hidden = true;
    }, 3600);
  }
}

new ConfiguracionTerminal().iniciar();

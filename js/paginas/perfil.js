class PerfilTerminal {
  constructor() {
    this.estadisticas = document.querySelector('[data-perfil-estadisticas]');
    this.actividad = document.querySelector('[data-perfil-actividad]');
    this.botonExportar = document.querySelector('[data-accion-perfil="exportar"]');
    this.botonEditar = document.querySelector('[data-accion-perfil="editar"]');
    this.mensaje = document.querySelector('[data-mensaje-perfil]');
  }

  iniciar() {
    this.renderizar();
    this.registrarAcciones();
  }

  renderizar() {
    this.renderizarEstadisticas();
    this.renderizarActividad();
  }

  renderizarEstadisticas() {
    if (!this.estadisticas) {
      return;
    }

    const ordenes = window.OrdenesSurcos.obtenerOrdenesUsuario();
    const resumen = window.OrdenesSurcos.calcularResumen(ordenes);
    const ventaja = resumen.total ? Math.round((resumen.ahorro / resumen.total) * 100) : 0;

    this.estadisticas.innerHTML = `
      <div class="box">Pools Ganados<b class="tab">${resumen.ganadas}</b></div>
      <div class="box">Total Ahorrado<b class="tab">${window.FormatoSurcos.moneda(resumen.ahorro)}</b></div>
      <div class="box">Ventaja de Rendimiento<b class="tab">+${ventaja}%</b></div>
    `;
  }

  renderizarActividad() {
    if (!this.actividad) {
      return;
    }

    const registros = this.obtenerActividad();
    this.actividad.innerHTML = registros.length
      ? registros.map((registro) => this.crearFilaActividad(registro)).join('')
      : this.crearActividadVacia();
  }

  obtenerActividad() {
    const actividades = window.EstadoSurcos.obtenerColeccion('actividad').map((registro) => ({
      fecha: registro.fecha,
      texto: registro.texto
    }));

    const ordenes = window.OrdenesSurcos.obtenerOrdenesUsuario().map((orden) => ({
      fecha: orden.fecha,
      texto: `${orden.textoGrupo} - ${orden.producto}`
    }));

    return [...actividades, ...ordenes]
      .sort((primera, segunda) => new Date(segunda.fecha) - new Date(primera.fecha))
      .slice(0, 8);
  }

  crearFilaActividad(registro) {
    return `
      <div class="item">
        <div class="dt">${window.FormatoSurcos.fechaCorta(registro.fecha).toUpperCase()}</div>
        <div class="tx">${registro.texto}</div>
      </div>
    `;
  }

  crearActividadVacia() {
    return `
      <div class="estado-vacio">
        <strong>Sin actividad todavia.</strong>
        <span>Cuando te comprometas a un pool, el movimiento aparecera aqui.</span>
      </div>
    `;
  }

  registrarAcciones() {
    this.botonExportar?.addEventListener('click', () => this.exportarDatos());
    this.botonEditar?.addEventListener('click', () => this.mostrarMensaje('La edicion completa queda pendiente para la fase de configuracion.'));
  }

  exportarDatos() {
    const usuario = window.AutenticacionSurcos.obtenerUsuarioActual()
      || window.EstadoSurcos.obtenerEstado().usuarioDemo;
    const datos = {
      usuario,
      ordenes: window.OrdenesSurcos.obtenerOrdenesUsuario(),
      actividad: this.obtenerActividad()
    };
    const archivo = new Blob([JSON.stringify(datos, null, 2)], { type: 'application/json;charset=utf-8' });
    const enlace = document.createElement('a');
    enlace.href = URL.createObjectURL(archivo);
    enlace.download = 'perfil-surcos.json';
    enlace.click();
    URL.revokeObjectURL(enlace.href);
    this.mostrarMensaje('Datos del perfil exportados.');
  }

  mostrarMensaje(texto) {
    if (this.mensaje) {
      this.mensaje.textContent = texto;
      this.mensaje.hidden = false;
    }
  }
}

new PerfilTerminal().iniciar();

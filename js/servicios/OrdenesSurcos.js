class OrdenesSurcos {
  static obtenerOrdenesUsuario() {
    const usuario = window.AutenticacionSurcos.obtenerUsuarioActual()
      || window.EstadoSurcos.obtenerEstado().usuarioDemo;

    if (!usuario) {
      return [];
    }

    return window.EstadoSurcos.obtenerColeccion('ordenes')
      .filter((orden) => orden.usuarioId === usuario.id)
      .map((orden) => this.completarOrden(orden))
      .sort((primera, segunda) => new Date(segunda.fecha) - new Date(primera.fecha));
  }

  static obtenerOrdenesActivas() {
    return this.obtenerOrdenesUsuario()
      .filter((orden) => !['entregado', 'cancelado'].includes(orden.estadoEntrega));
  }

  static completarOrden(orden) {
    const grupo = window.GruposCompraSurcos.obtenerGrupoPorId(orden.grupoCompraId);
    const porcentaje = grupo ? window.GruposCompraSurcos.calcularPorcentaje(grupo) : 0;
    const cantidad = grupo?.cantidadMinima || 1;
    const unidad = grupo?.unidad || 'unidad';
    const ahorro = grupo ? Math.max(0, (grupo.precioMercado - grupo.precioGrupal) * cantidad) : 0;

    return {
      ...orden,
      grupo,
      porcentaje,
      cantidad,
      unidad,
      ahorro,
      textoGrupo: this.formatearEstadoGrupo(orden.estadoGrupo),
      textoEntrega: this.formatearEstadoEntrega(orden.estadoEntrega),
      claseGrupo: this.obtenerClaseGrupo(orden.estadoGrupo),
      claseEntrega: this.obtenerClaseEntrega(orden.estadoEntrega)
    };
  }

  static calcularResumen(ordenes = this.obtenerOrdenesUsuario()) {
    const total = ordenes.reduce((suma, orden) => suma + Number(orden.monto || 0), 0);
    const ahorro = ordenes.reduce((suma, orden) => suma + Number(orden.ahorro || 0), 0);
    const ganadas = ordenes.filter((orden) => orden.estadoGrupo === 'ganado').length;

    return {
      total,
      ahorro,
      ganadas,
      cantidad: ordenes.length
    };
  }

  static formatearEstadoGrupo(estado) {
    const textos = {
      ganado: 'GANADO',
      pendiente: 'PENDIENTE',
      fallido: 'POOL FALLIDO'
    };

    return textos[estado] || 'POOL ACTIVO';
  }

  static formatearEstadoEntrega(estado) {
    const textos = {
      entregado: 'ENTREGADO',
      transito: 'EN TRANSITO',
      programado: 'PROGRAMADO',
      cancelado: 'CANCELADO'
    };

    return textos[estado] || 'COMPROMETIENDO';
  }

  static obtenerClaseGrupo(estado) {
    if (estado === 'fallido') {
      return 's-fail';
    }

    return estado === 'ganado' ? 's-del' : 's-act';
  }

  static obtenerClaseEntrega(estado) {
    const clases = {
      entregado: 's-del',
      transito: 's-tr',
      programado: 's-act',
      cancelado: 's-fail'
    };

    return clases[estado] || 's-act';
  }
}

window.OrdenesSurcos = OrdenesSurcos;

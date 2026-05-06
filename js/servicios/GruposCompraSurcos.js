class GruposCompraSurcos {
  static obtenerGruposActivos() {
    return window.EstadoSurcos.obtenerColeccion('gruposCompra')
      .filter((grupo) => grupo.estado === 'activo')
      .map((grupo) => this.completarGrupo(grupo));
  }

  static obtenerGrupoPorId(id) {
    const grupo = window.EstadoSurcos.buscarPorId('gruposCompra', id);
    return grupo ? this.completarGrupo(grupo) : null;
  }

  static obtenerGrupoDesdeDireccion() {
    const parametros = new URLSearchParams(window.location.search);
    const id = parametros.get('id');
    const grupos = this.obtenerGruposActivos();
    return this.obtenerGrupoPorId(id) || grupos[0] || null;
  }

  static completarGrupo(grupo) {
    const productor = window.EstadoSurcos.buscarPorId('productores', grupo.productorId) || {};
    return {
      ...grupo,
      productor,
      porcentaje: this.calcularPorcentaje(grupo),
      ahorro: Math.max(0, grupo.precioMercado - grupo.precioGrupal)
    };
  }

  static calcularPorcentaje(grupo) {
    if (!grupo.personasObjetivo) {
      return 0;
    }

    return Math.min(100, Math.round((grupo.personasActuales / grupo.personasObjetivo) * 100));
  }

  static usuarioEstaComprometido(grupoId) {
    const usuario = window.AutenticacionSurcos.obtenerUsuarioActual();

    if (!usuario) {
      return false;
    }

    return window.EstadoSurcos.obtenerColeccion('ordenes')
      .some((orden) => orden.grupoCompraId === grupoId && orden.usuarioId === usuario.id);
  }

  static comprometerse(grupoId) {
    const usuario = window.AutenticacionSurcos.obtenerUsuarioActual();

    if (!usuario) {
      return { requiereIngreso: true };
    }

    const grupo = this.obtenerGrupoPorId(grupoId);

    if (!grupo) {
      return { exito: false, mensaje: 'No se encontro este grupo de compra.' };
    }

    const ordenes = window.EstadoSurcos.obtenerColeccion('ordenes');
    const ordenExistente = ordenes.find((orden) => orden.grupoCompraId === grupo.id && orden.usuarioId === usuario.id);

    if (ordenExistente) {
      return { exito: false, mensaje: 'Ya estas comprometido con este pool.' };
    }

    const personasActuales = Math.min(grupo.personasObjetivo, grupo.personasActuales + 1);
    const estadoGrupo = personasActuales >= grupo.personasObjetivo ? 'ganado' : 'pendiente';
    const metodoPago = window.MetodosPagoSurcos?.obtenerPrincipal?.() || null;
    window.EstadoSurcos.actualizar('gruposCompra', grupo.id, { personasActuales });

    const orden = {
      id: window.FormatoSurcos.crearId('ord', `${grupo.id} ${usuario.id}`),
      grupoCompraId: grupo.id,
      usuarioId: usuario.id,
      producto: `${grupo.producto} ${grupo.variedad}`,
      origen: grupo.origen,
      monto: Number((grupo.precioGrupal * grupo.cantidadMinima).toFixed(2)),
      metodoPagoId: metodoPago?.id || null,
      metodoPagoEtiqueta: metodoPago ? `${metodoPago.tipo} ${metodoPago.ultimos}` : 'Metodo pendiente',
      estadoGrupo,
      estadoEntrega: 'programado',
      fecha: new Date().toISOString().slice(0, 10)
    };

    window.EstadoSurcos.agregar('ordenes', orden);
    window.EstadoSurcos.agregar('actividad', {
      id: window.FormatoSurcos.crearId('act', `${grupo.id} ${usuario.id}`),
      tipo: 'grupo',
      texto: `Comprometido al grupo ${grupo.producto} ${grupo.variedad}`,
      fecha: orden.fecha
    });

    return {
      exito: true,
      mensaje: estadoGrupo === 'ganado'
        ? 'Compromiso registrado. El pool alcanzo su meta.'
        : 'Compromiso registrado. Tu orden quedo en el terminal.',
      grupo: this.obtenerGrupoPorId(grupo.id),
      orden
    };
  }
}

window.GruposCompraSurcos = GruposCompraSurcos;

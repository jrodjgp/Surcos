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
    return Boolean(this.obtenerOrdenActivaUsuario(grupoId));
  }

  static obtenerOrdenActivaUsuario(grupoId) {
    return this.obtenerOrdenesUsuarioGrupo(grupoId)
      .find((orden) => !this.ordenEstaCancelada(orden)) || null;
  }

  static obtenerOrdenUsuarioGrupo(grupoId, usuario = window.AutenticacionSurcos.obtenerUsuarioActual()) {
    return this.obtenerOrdenesUsuarioGrupo(grupoId, usuario)[0] || null;
  }

  static obtenerOrdenesUsuarioGrupo(grupoId, usuario = window.AutenticacionSurcos.obtenerUsuarioActual()) {
    if (!usuario) {
      return [];
    }

    return window.EstadoSurcos.obtenerColeccion('ordenes')
      .filter((orden) => orden.grupoCompraId === grupoId && orden.usuarioId === usuario.id)
      .sort((primera, segunda) => this.obtenerFechaOrden(segunda) - this.obtenerFechaOrden(primera));
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

    if (grupo.estado !== 'activo') {
      return { exito: false, mensaje: 'Este pool ya no esta activo.' };
    }

    const ordenExistente = this.obtenerOrdenActivaUsuario(grupo.id);

    if (ordenExistente) {
      return { exito: false, mensaje: 'Ya estas comprometido con este pool.' };
    }

    const ordenAnterior = this.obtenerOrdenUsuarioGrupo(grupo.id, usuario);

    if (ordenAnterior?.estadoEntrega === 'entregado') {
      return { exito: false, mensaje: 'Este pool ya fue entregado para tu cuenta.' };
    }

    const personasActuales = Math.min(grupo.personasObjetivo, grupo.personasActuales + 1);
    const estadoGrupo = personasActuales >= grupo.personasObjetivo ? 'ganado' : 'pendiente';
    const metodoPago = window.MetodosPagoSurcos?.obtenerPrincipal?.() || null;
    window.EstadoSurcos.actualizar('gruposCompra', grupo.id, { personasActuales });

    const datosOrden = {
      grupoCompraId: grupo.id,
      usuarioId: usuario.id,
      producto: `${grupo.producto} ${grupo.variedad}`,
      origen: grupo.origen,
      monto: Number((grupo.precioGrupal * grupo.cantidadMinima).toFixed(2)),
      metodoPagoId: metodoPago?.id || null,
      metodoPagoEtiqueta: metodoPago ? `${metodoPago.tipo} ${metodoPago.ultimos}` : 'Metodo pendiente',
      estadoGrupo,
      estadoEntrega: 'programado',
      fecha: new Date().toISOString().slice(0, 10),
      fechaCancelacion: null
    };
    const orden = ordenAnterior
      ? window.EstadoSurcos.actualizar('ordenes', ordenAnterior.id, datosOrden)
      : window.EstadoSurcos.agregar('ordenes', {
        id: window.FormatoSurcos.crearId('ord', `${grupo.id} ${usuario.id}`),
        ...datosOrden
      });

    this.consolidarOrdenesUsuarioGrupo(grupo.id, usuario.id, orden.id);
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

  static cancelarCompromiso(grupoId) {
    const usuario = window.AutenticacionSurcos.obtenerUsuarioActual();

    if (!usuario) {
      return { requiereIngreso: true };
    }

    const grupo = this.obtenerGrupoPorId(grupoId);
    const orden = this.obtenerOrdenActivaUsuario(grupoId);

    if (!grupo || !orden) {
      return { exito: false, mensaje: 'No tienes un compromiso activo en este pool.' };
    }

    if (orden.estadoEntrega === 'entregado') {
      return { exito: false, mensaje: 'No se puede cancelar un pool ya entregado.' };
    }

    const personasActuales = Math.max(0, Number(grupo.personasActuales || 0) - 1);
    window.EstadoSurcos.actualizar('gruposCompra', grupo.id, { personasActuales });
    window.EstadoSurcos.actualizar('ordenes', orden.id, {
      estadoGrupo: 'cancelado',
      estadoEntrega: 'cancelado',
      fechaCancelacion: new Date().toISOString().slice(0, 10)
    });
    this.consolidarOrdenesUsuarioGrupo(grupo.id, usuario.id, orden.id);
    window.EstadoSurcos.agregar('actividad', {
      id: window.FormatoSurcos.crearId('act', `${grupo.id} ${usuario.id} salida`),
      tipo: 'grupo',
      texto: `Saliste del pool ${grupo.producto} ${grupo.variedad}`,
      fecha: new Date().toISOString().slice(0, 10)
    });

    return {
      exito: true,
      mensaje: 'Saliste del pool. Tu compromiso quedo cancelado en el terminal.',
      grupo: this.obtenerGrupoPorId(grupo.id),
      orden: window.EstadoSurcos.buscarPorId('ordenes', orden.id)
    };
  }

  static consolidarOrdenesUsuarioGrupo(grupoId, usuarioId, ordenIdPrincipal) {
    const ordenes = window.EstadoSurcos.obtenerColeccion('ordenes');
    const relacionadas = ordenes.filter((orden) => orden.grupoCompraId === grupoId && orden.usuarioId === usuarioId);

    if (relacionadas.length <= 1) {
      return;
    }

    const principal = relacionadas.find((orden) => orden.id === ordenIdPrincipal)
      || relacionadas[0];
    const depuradas = ordenes.filter((orden) => (
      orden.grupoCompraId !== grupoId
      || orden.usuarioId !== usuarioId
      || orden.id === principal.id
    ));

    window.EstadoSurcos.guardarColeccion('ordenes', depuradas);
  }

  static ordenEstaCancelada(orden) {
    return orden.estadoEntrega === 'cancelado' || orden.estadoGrupo === 'cancelado';
  }

  static obtenerFechaOrden(orden) {
    return new Date(orden.fechaCancelacion || orden.fecha || 0).getTime();
  }
}

window.GruposCompraSurcos = GruposCompraSurcos;

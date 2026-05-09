class MetodosPagoSurcos {
  static obtenerMetodos() {
    return window.EstadoSurcos.obtenerColeccion('metodosPago');
  }

  static obtenerPrincipal() {
    return this.obtenerMetodos().find((metodo) => metodo.principal) || this.obtenerMetodos()[0] || null;
  }

  static agregarMetodo(datos) {
    const metodos = this.obtenerMetodos();
    const tipo = String(datos.tipo || '').trim().toUpperCase();
    const etiqueta = String(datos.etiqueta || '').trim();
    const ultimos = String(datos.ultimos || '').trim().slice(-4);

    if (!tipo || !etiqueta || !ultimos) {
      return { exito: false, mensaje: 'Completa tipo, etiqueta y ultimos digitos.' };
    }

    const metodo = {
      id: window.FormatoSurcos.crearId('pago', `${tipo} ${ultimos}`),
      tipo,
      etiqueta,
      ultimos,
      principal: metodos.length === 0
    };

    window.EstadoSurcos.agregar('metodosPago', metodo);
    return { exito: true, metodo, mensaje: 'Metodo agregado al terminal.' };
  }

  static marcarPrincipal(id) {
    const metodos = this.obtenerMetodos().map((metodo) => ({
      ...metodo,
      principal: metodo.id === id
    }));

    window.EstadoSurcos.guardarColeccion('metodosPago', metodos);
    return { exito: true, mensaje: 'Metodo principal actualizado.' };
  }

  static eliminarMetodo(id) {
    const metodos = this.obtenerMetodos();

    if (metodos.length <= 1) {
      return { exito: false, mensaje: 'Manten al menos un metodo de pago.' };
    }

    const eliminado = metodos.find((metodo) => metodo.id === id);
    let restantes = metodos.filter((metodo) => metodo.id !== id);

    if (eliminado?.principal && restantes.length) {
      restantes = restantes.map((metodo, indice) => ({
        ...metodo,
        principal: indice === 0
      }));
    }

    window.EstadoSurcos.guardarColeccion('metodosPago', restantes);
    return { exito: true, mensaje: 'Metodo eliminado del terminal.' };
  }

  static formatearMetodo(metodo) {
    if (metodo.tipo.includes('YAPPY')) {
      return metodo.etiqueta;
    }

    return `&bull;&bull;&bull;&bull; &bull;&bull;&bull;&bull; &bull;&bull;&bull;&bull; ${metodo.ultimos}`;
  }
}

window.MetodosPagoSurcos = MetodosPagoSurcos;

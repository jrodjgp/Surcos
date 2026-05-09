class Cosecha {
  constructor({
    producto,
    variedad,
    cantidadKg,
    precioMinimo,
    ubicacion,
    ventana,
    modeloEntrega
  }) {
    this.producto = producto;
    this.variedad = variedad;
    this.cantidadKg = Number(cantidadKg);
    this.precioMinimo = Number(precioMinimo);
    this.ubicacion = ubicacion;
    this.ventana = ventana;
    this.modeloEntrega = modeloEntrega;
  }

  crearGrupoCompra({ productorId, nodoRetiro, imagen }) {
    const cantidadMinima = this.calcularCantidadMinima();
    const personasObjetivo = Math.max(8, Math.min(60, Math.ceil(this.cantidadKg / cantidadMinima)));

    return {
      id: window.FormatoSurcos.crearId('grupo', `${this.producto} ${this.variedad}`),
      productorId,
      producto: this.producto,
      variedad: this.variedad,
      categoria: this.crearCategoria(),
      origen: this.ubicacion,
      imagen,
      precioMercado: Number((this.precioMinimo * 1.45).toFixed(2)),
      precioGrupal: Number(this.precioMinimo.toFixed(2)),
      unidad: 'kg',
      personasActuales: 0,
      personasObjetivo,
      cantidadMinima,
      fechaCierre: this.calcularFecha(14, true),
      fechaEntrega: this.calcularFecha(21, false),
      estado: 'activo',
      modeloEntrega: this.modeloEntrega,
      nodoRetiro
    };
  }

  calcularCantidadMinima() {
    if (this.modeloEntrega === 'Lote Empresarial') {
      return Math.max(25, Math.min(this.cantidadKg, 200));
    }

    if (this.modeloEntrega === 'Envio a Domicilio') {
      return Math.max(5, Math.min(this.cantidadKg, 10));
    }

    return Math.max(5, Math.min(this.cantidadKg, 25));
  }

  calcularFecha(dias, conHora) {
    const fecha = new Date();
    fecha.setDate(fecha.getDate() + dias);

    if (conHora) {
      fecha.setHours(23, 59, 0, 0);
      return fecha.toISOString();
    }

    return fecha.toISOString().slice(0, 10);
  }

  crearCategoria() {
    return String(this.producto || 'cosecha')
      .normalize('NFD')
      .replace(/[\u0300-\u036f]/g, '')
      .toLowerCase()
      .split(/\s+/)[0] || 'cosecha';
  }

  get ingresoEstimado() {
    return this.cantidadKg * this.precioMinimo;
  }

  get resumen() {
    return `${this.producto} (${this.variedad}) desde ${this.ubicacion}`;
  }

  formatearPrecio(valor) {
    return new Intl.NumberFormat('es-PA', {
      style: 'currency',
      currency: 'PAB',
      minimumFractionDigits: 2
    }).format(valor);
  }

  crearMensajeConfirmacion() {
    const ingreso = this.formatearPrecio(this.ingresoEstimado);
    return `Cosecha lista para revision: ${this.resumen}. Ingreso estimado del lote: ${ingreso}.`;
  }
}

window.Cosecha = Cosecha;

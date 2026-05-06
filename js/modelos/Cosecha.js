export class Cosecha {
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


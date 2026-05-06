class HistorialOrdenes {
  constructor() {
    this.cuerpoTabla = document.querySelector('[data-historial-ordenes]');
    this.resumen = document.querySelector('[data-historial-resumen]');
    this.rango = document.querySelector('[data-historial-rango]');
    this.filtros = document.querySelectorAll('[data-filtro-historial]');
    this.filtroActivo = 'todas';
  }

  iniciar() {
    this.registrarFiltros();
    this.renderizar();
  }

  registrarFiltros() {
    this.filtros.forEach((boton) => {
      boton.addEventListener('click', () => {
        this.filtroActivo = boton.dataset.filtroHistorial;
        this.filtros.forEach((control) => control.classList.toggle('active', control === boton));
        this.renderizar();
      });
    });
  }

  renderizar() {
    const ordenes = this.filtrarOrdenes(window.OrdenesSurcos.obtenerOrdenesUsuario());
    const resumen = window.OrdenesSurcos.calcularResumen(window.OrdenesSurcos.obtenerOrdenesUsuario());

    if (this.cuerpoTabla) {
      this.cuerpoTabla.innerHTML = ordenes.length
        ? ordenes.map((orden) => this.crearFila(orden)).join('')
        : this.crearFilaVacia();
    }

    if (this.resumen) {
      this.resumen.textContent = `TOTAL COMPROMETIDO ESTE CICLO: ${window.FormatoSurcos.moneda(resumen.total)} - AHORRO ESTIMADO: ${window.FormatoSurcos.moneda(resumen.ahorro)} - POOLS GANADOS: ${resumen.ganadas}/${resumen.cantidad}`;
    }

    if (this.rango) {
      this.rango.value = this.crearRango(window.OrdenesSurcos.obtenerOrdenesUsuario());
    }
  }

  filtrarOrdenes(ordenes) {
    if (this.filtroActivo === 'entregadas') {
      return ordenes.filter((orden) => orden.estadoEntrega === 'entregado');
    }

    if (this.filtroActivo === 'canceladas') {
      return ordenes.filter((orden) => orden.estadoEntrega === 'cancelado' || orden.estadoGrupo === 'fallido');
    }

    return ordenes;
  }

  crearFila(orden) {
    const grupo = orden.grupo || {};
    const total = window.FormatoSurcos.moneda(orden.monto);

    return `
      <tr>
        <td>
          <div class="prod">
            <div class="thb">${grupo.imagen ? `<img alt="${orden.producto}" src="${grupo.imagen}" />` : 'lote'}</div>
            <div>${orden.producto} - ${orden.origen}</div>
          </div>
        </td>
        <td class="tab">${total}</td>
        <td><span class="status ${orden.claseGrupo}">${orden.textoGrupo}</span>${this.crearMiniBarra(orden)}</td>
        <td><span class="status ${orden.claseEntrega}">${orden.textoEntrega}</span></td>
        <td class="tab ${orden.estadoEntrega === 'cancelado' ? 'price-cancelled' : ''}">${total}</td>
      </tr>
    `;
  }

  crearMiniBarra(orden) {
    if (orden.estadoGrupo !== 'pendiente') {
      return '';
    }

    return `<div class="mini"><i style="width:${orden.porcentaje}%"></i></div>`;
  }

  crearFilaVacia() {
    return `
      <tr>
        <td colspan="5">
          <div class="estado-vacio">
            <strong>No hay ordenes para este filtro.</strong>
            <span>Cambia el filtro o compromete un nuevo pool desde el marketplace.</span>
          </div>
        </td>
      </tr>
    `;
  }

  crearRango(ordenes) {
    if (!ordenes.length) {
      return 'SIN ORDENES';
    }

    const fechas = ordenes.map((orden) => new Date(orden.fecha)).sort((primera, segunda) => primera - segunda);
    const primera = window.FormatoSurcos.fechaCorta(fechas[0].toISOString());
    const ultima = window.FormatoSurcos.fechaCorta(fechas[fechas.length - 1].toISOString());

    return `${primera} - ${ultima}`.toUpperCase();
  }
}

new HistorialOrdenes().iniciar();

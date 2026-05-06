class TerminalUsuario {
  constructor() {
    this.cuerpoTabla = document.querySelector('[data-terminal-activos]');
    this.totalAhorro = document.querySelector('[data-terminal-ahorro]');
    this.insignia = document.querySelector('[data-terminal-insignia]');
    this.botonExportar = document.querySelector('[data-accion-terminal="exportar"]');
  }

  iniciar() {
    this.renderizar();
    this.registrarAcciones();
  }

  renderizar() {
    const ordenes = window.OrdenesSurcos.obtenerOrdenesActivas();
    const todas = window.OrdenesSurcos.obtenerOrdenesUsuario();
    const resumen = window.OrdenesSurcos.calcularResumen(todas);

    if (this.totalAhorro) {
      this.totalAhorro.textContent = window.FormatoSurcos.moneda(resumen.ahorro);
    }

    if (this.insignia) {
      this.insignia.textContent = `${ordenes.length} ofertas en vivo`;
    }

    if (!this.cuerpoTabla) {
      return;
    }

    this.cuerpoTabla.innerHTML = ordenes.length
      ? ordenes.map((orden) => this.crearFila(orden)).join('')
      : this.crearFilaVacia();
  }

  crearFila(orden) {
    const grupo = orden.grupo || {};
    const claseBarra = orden.porcentaje >= 80 ? 'leaf' : 'ochre';
    const fechaEntrega = grupo.fechaEntrega ? window.FormatoSurcos.fechaCorta(grupo.fechaEntrega) : window.FormatoSurcos.fechaCorta(orden.fecha);

    return `
      <tr>
        <td>
          <div class="prod">
            <div class="thb">${grupo.imagen ? `<img alt="${orden.producto}" src="${grupo.imagen}" />` : 'lote'}</div>
            <div>
              <div class="p-name">${orden.producto}</div>
              <div class="p-origin">${orden.origen}</div>
            </div>
          </div>
        </td>
        <td class="contrib"><b>${orden.cantidad} ${orden.unidad}</b><small class="tab">${window.FormatoSurcos.moneda(orden.monto)} comprometido</small></td>
        <td>
          <div class="pool-prog">
            <div class="pool-meta"><span>${grupo.personasActuales || 0} / ${grupo.personasObjetivo || 0}</span><span class="pool-badge ${claseBarra}">${orden.textoGrupo}</span></div>
            <div class="mini-bar"><i class="${claseBarra}" style="width:${orden.porcentaje}%"></i></div>
          </div>
        </td>
        <td>
          <div class="est-date tab">${fechaEntrega}</div>
          <div class="est-status ${orden.estadoEntrega === 'transito' ? 'st-terra' : 'st-muted'}">${orden.textoEntrega}</div>
        </td>
      </tr>
    `;
  }

  crearFilaVacia() {
    return `
      <tr>
        <td colspan="4">
          <div class="estado-vacio">
            <strong>No tienes pools activos.</strong>
            <span>Explora el marketplace y compromete un lote para verlo aqui.</span>
            <a href="marketplace_terminal.html">Ir al Marketplace</a>
          </div>
        </td>
      </tr>
    `;
  }

  registrarAcciones() {
    if (!this.botonExportar) {
      return;
    }

    this.botonExportar.addEventListener('click', () => this.exportarManifiesto());
  }

  exportarManifiesto() {
    const ordenes = window.OrdenesSurcos.obtenerOrdenesUsuario();
    const filas = [
      ['orden', 'producto', 'origen', 'monto', 'estado_grupo', 'estado_entrega', 'fecha'],
      ...ordenes.map((orden) => [
        orden.id,
        orden.producto,
        orden.origen,
        orden.monto,
        orden.estadoGrupo,
        orden.estadoEntrega,
        orden.fecha
      ])
    ];

    const texto = filas.map((fila) => fila.map((valor) => `"${String(valor).replace(/"/g, '""')}"`).join(',')).join('\n');
    const archivo = new Blob([texto], { type: 'text/csv;charset=utf-8' });
    const enlace = document.createElement('a');
    enlace.href = URL.createObjectURL(archivo);
    enlace.download = 'manifiesto-surcos.csv';
    enlace.click();
    URL.revokeObjectURL(enlace.href);
  }
}

new TerminalUsuario().iniciar();

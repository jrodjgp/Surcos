class TerminalUsuario {
  constructor() {
    this.cuerpoTabla = document.querySelector('[data-terminal-activos]');
    this.totalAhorro = document.querySelector('[data-terminal-ahorro]');
    this.insignia = document.querySelector('[data-terminal-insignia]');
    this.botonExportar = document.querySelector('[data-accion-terminal="exportar"]');
    this.mensaje = document.querySelector('[data-mensaje-terminal]');
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
        <td>${this.crearAcciones(orden)}</td>
      </tr>
    `;
  }

  crearAcciones(orden) {
    const enlace = orden.grupo
      ? `<a class="accion-tabla" href="pool_detail.html?id=${orden.grupoCompraId}">Ver</a>`
      : '';
    const salida = orden.grupo && orden.estadoEntrega !== 'entregado'
      ? `<button class="accion-tabla peligro" type="button" data-accion-orden="salir" data-grupo-id="${orden.grupoCompraId}">Salir</button>`
      : '';

    return `<div class="acciones-tabla">${enlace}${salida}</div>`;
  }

  crearFilaVacia() {
    return `
      <tr>
        <td colspan="5">
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
    if (this.botonExportar) {
      this.botonExportar.addEventListener('click', () => this.exportarManifiesto());
    }

    document.addEventListener('click', (evento) => {
      const boton = evento.target.closest('[data-accion-orden="salir"]');

      if (!boton) {
        return;
      }

      evento.preventDefault();
      const resultado = window.GruposCompraSurcos.cancelarCompromiso(boton.dataset.grupoId);

      if (resultado.requiereIngreso) {
        const retorno = encodeURIComponent('mi_terminal_dashboard.html');
        window.location.href = `ingreso.html?retorno=${retorno}`;
        return;
      }

      this.mostrarMensaje(resultado.mensaje);

      if (resultado.exito) {
        this.renderizar();
      }
    });
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

  mostrarMensaje(texto) {
    if (!this.mensaje) {
      return;
    }

    this.mensaje.textContent = texto;
    this.mensaje.hidden = false;
  }
}

new TerminalUsuario().iniciar();

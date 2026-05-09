class PagosTerminal {
  constructor() {
    this.lista = document.querySelector('[data-lista-pagos]');
    this.ciclo = document.querySelector('[data-ciclo-pagos]');
    this.formulario = document.querySelector('[data-formulario-pago]');
    this.mensaje = document.querySelector('[data-mensaje-pago]');
    this.botonMostrar = document.querySelector('[data-accion-pago="mostrar-formulario"]');
  }

  iniciar() {
    this.renderizar();
    this.registrarAcciones();
  }

  registrarAcciones() {
    this.lista?.addEventListener('click', (evento) => this.manejarAccionMetodo(evento));
    this.botonMostrar?.addEventListener('click', () => this.alternarFormulario());
    this.formulario?.addEventListener('submit', (evento) => this.agregarMetodo(evento));
  }

  manejarAccionMetodo(evento) {
    const control = evento.target.closest('[data-accion-pago]');

    if (!control) {
      return;
    }

    const id = control.dataset.metodoId;
    let resultado = null;

    if (control.dataset.accionPago === 'principal') {
      resultado = window.MetodosPagoSurcos.marcarPrincipal(id);
    }

    if (control.dataset.accionPago === 'eliminar') {
      resultado = window.MetodosPagoSurcos.eliminarMetodo(id);
    }

    if (resultado) {
      this.mostrarMensaje(resultado.mensaje);
      this.renderizar();
    }
  }

  alternarFormulario() {
    if (!this.formulario) {
      return;
    }

    this.formulario.hidden = !this.formulario.hidden;
  }

  agregarMetodo(evento) {
    evento.preventDefault();

    if (!this.formulario.checkValidity()) {
      this.formulario.classList.add('was-validated');
      this.mostrarMensaje('Revisa los campos del metodo de pago.');
      return;
    }

    const datos = new FormData(this.formulario);
    const resultado = window.MetodosPagoSurcos.agregarMetodo({
      tipo: datos.get('tipo'),
      etiqueta: datos.get('etiqueta'),
      ultimos: datos.get('ultimos')
    });

    this.mostrarMensaje(resultado.mensaje);

    if (resultado.exito) {
      this.formulario.reset();
      this.formulario.classList.remove('was-validated');
      this.formulario.hidden = true;
      this.renderizar();
    }
  }

  renderizar() {
    this.renderizarMetodos();
    this.renderizarCiclo();
  }

  renderizarMetodos() {
    if (!this.lista) {
      return;
    }

    const metodos = window.MetodosPagoSurcos.obtenerMetodos();
    this.lista.innerHTML = metodos.map((metodo) => this.crearTarjetaMetodo(metodo)).join('');
  }

  crearTarjetaMetodo(metodo) {
    const etiqueta = metodo.principal ? 'PRINCIPAL' : 'RESPALDO';
    const botonPrincipal = metodo.principal
      ? ''
      : `<button class="pay-action" type="button" data-accion-pago="principal" data-metodo-id="${metodo.id}">Hacer Principal</button>`;

    return `
      <article class="m-card">
        <div class="m-row">
          <span class="icon">[${metodo.tipo}]</span>
          <span class="num tab">${window.MetodosPagoSurcos.formatearMetodo(metodo)}</span>
          <span class="tag">${etiqueta}</span>
          ${botonPrincipal}
          <button class="rm" type="button" data-accion-pago="eliminar" data-metodo-id="${metodo.id}">ELIMINAR</button>
        </div>
      </article>
    `;
  }

  renderizarCiclo() {
    if (!this.ciclo || !window.OrdenesSurcos) {
      return;
    }

    const ordenes = window.OrdenesSurcos.obtenerOrdenesUsuario().slice(0, 4);
    this.ciclo.innerHTML = ordenes.length
      ? ordenes.map((orden) => this.crearFilaCiclo(orden)).join('')
      : '<tr><td colspan="2">Sin cargos registrados</td></tr>';
  }

  crearFilaCiclo(orden) {
    return `
      <tr>
        <td>${window.FormatoSurcos.fechaCorta(orden.fecha)} - ${orden.producto}</td>
        <td class="tab">${window.FormatoSurcos.moneda(orden.monto)}</td>
      </tr>
    `;
  }

  mostrarMensaje(texto) {
    if (this.mensaje) {
      this.mensaje.textContent = texto;
      this.mensaje.hidden = false;
    }
  }
}

new PagosTerminal().iniciar();

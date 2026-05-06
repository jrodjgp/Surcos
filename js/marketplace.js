class VistaMercado {
  constructor() {
    this.lista = document.querySelector('[data-lista-grupos]');
    this.total = document.querySelector('[data-total-grupos]');
  }

  iniciar() {
    this.iniciarFormularioCosecha();
    this.renderizarGrupos();
  }

  iniciarFormularioCosecha() {
    const formularioCosecha = document.getElementById('formularioCosecha');

    if (formularioCosecha) {
      new window.FormularioCosecha(formularioCosecha).iniciar();
    }
  }

  renderizarGrupos() {
    if (!this.lista || !window.GruposCompraSurcos) {
      return;
    }

    const grupos = window.GruposCompraSurcos.obtenerGruposActivos();
    this.lista.innerHTML = grupos.map((grupo) => this.crearTarjeta(grupo)).join('');

    if (this.total) {
      this.total.textContent = `${grupos.length} pools abiertos`;
    }
  }

  crearTarjeta(grupo) {
    const porcentaje = window.GruposCompraSurcos.calcularPorcentaje(grupo);
    const claseAvance = porcentaje >= 80 ? 'terra' : '';

    return `
      <article class="card">
        <div class="card-img">
          <img alt="${grupo.producto} ${grupo.origen}" src="${grupo.imagen}" />
          <div class="card-badge">${grupo.origen}</div>
        </div>
        <h3 class="name">${grupo.producto} - ${grupo.variedad}</h3>
        <div class="price-row">
          <span class="price tab">${window.FormatoSurcos.moneda(grupo.precioGrupal)}<small>/${grupo.unidad}</small></span>
          <span class="retail tab">Retail: ${window.FormatoSurcos.moneda(grupo.precioMercado)}</span>
        </div>
        <div class="prog">
          <div class="prog-head">
            <span class="pool-cantidad">${grupo.personasActuales}/${grupo.personasObjetivo} personas</span>
            <span class="pool-porcentaje">${porcentaje}%</span>
          </div>
          <div class="bar"><i class="progress-fill ${claseAvance}" style="--pool-progress:${porcentaje}%"></i></div>
        </div>
        <div class="deadline">Cierra: ${window.FormatoSurcos.fechaCorta(grupo.fechaCierre)}</div>
        <a href="pool_detail.html?id=${grupo.id}" class="btn">Ver y Comprometerse</a>
      </article>
    `;
  }
}

new VistaMercado().iniciar();

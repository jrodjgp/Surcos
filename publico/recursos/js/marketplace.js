class VistaMercado {
  constructor() {
    this.lista = document.querySelector('[data-lista-grupos]');
    this.total = document.querySelector('[data-total-grupos]');
    this.lotesPublicados = document.querySelector('[data-lotes-publicados]');
    this.volumenProductor = document.querySelector('[data-volumen-productor]');
    this.poolsProductor = document.querySelector('[data-pools-productor]');
    this.ingresoProductor = document.querySelector('[data-ingreso-productor]');
    this.llenadoProductor = document.querySelector('[data-llenado-productor]');
    this.listaLotesProductor = document.querySelector('[data-lista-lotes-productor]');
  }

  iniciar() {
    this.iniciarFormularioCosecha();
    this.renderizarGrupos();
    this.renderizarResumenProductor();
    window.addEventListener('cosecha:publicada', () => {
      this.renderizarGrupos();
      this.renderizarResumenProductor();
    });
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

  renderizarResumenProductor() {
    if (!window.PublicacionesProductorSurcos) {
      return;
    }

    const grupos = window.PublicacionesProductorSurcos.obtenerGruposProductorActual();
    const volumen = grupos.reduce((total, grupo) => total + Number(grupo.cantidadMinima * grupo.personasObjetivo), 0);
    const ingreso = grupos.reduce((total, grupo) => (
      total + Number(grupo.precioGrupal * grupo.cantidadMinima * grupo.personasObjetivo)
    ), 0);
    const llenado = grupos.length
      ? Math.round(grupos.reduce((total, grupo) => total + window.GruposCompraSurcos.calcularPorcentaje(grupo), 0) / grupos.length)
      : 0;

    this.escribir(this.lotesPublicados, grupos.length);
    this.escribir(this.volumenProductor, `${window.FormatoSurcos.numero(volumen)} kg`);
    this.escribir(this.poolsProductor, grupos.length);
    this.escribir(this.ingresoProductor, window.FormatoSurcos.moneda(ingreso));
    this.escribir(this.llenadoProductor, `${llenado}%`);
    this.renderizarLotesProductor(grupos);
  }

  renderizarLotesProductor(grupos) {
    if (!this.listaLotesProductor) {
      return;
    }

    if (!grupos.length) {
      this.listaLotesProductor.innerHTML = '<p class="lotes-vacio">No tienes pools activos publicados en este ciclo.</p>';
      return;
    }

    this.listaLotesProductor.innerHTML = grupos.map((grupo) => `
      <article class="lote-productor">
        <div>
          <strong>${grupo.producto}</strong>
          <span>${grupo.variedad} - ${grupo.origen}</span>
        </div>
        <div class="lote-productor-meta">
          <span>${window.GruposCompraSurcos.calcularPorcentaje(grupo)}%</span>
          <a href="pool_detail.html?id=${grupo.id}">Ver</a>
        </div>
      </article>
    `).join('');
  }

  escribir(elemento, texto) {
    if (elemento) {
      elemento.textContent = texto;
    }
  }
}

new VistaMercado().iniciar();

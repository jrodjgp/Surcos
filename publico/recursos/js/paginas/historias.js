class HistoriasProductor {
  constructor() {
    this.imagenHero = document.querySelector('[data-historia-imagen]');
    this.volumen = document.querySelector('[data-historia-volumen]');
    this.titulo = document.querySelector('[data-historia-titulo]');
    this.responsable = document.querySelector('[data-productor-responsable]');
    this.ubicacion = document.querySelector('[data-productor-ubicacion]');
    this.varietal = document.querySelector('[data-productor-varietal]');
    this.articulo = document.querySelector('[data-historia-articulo]');
    this.cosecha = document.querySelector('[data-historia-cosecha]');
    this.asignacion = document.querySelector('[data-historia-asignacion]');
    this.cantidad = document.querySelector('[data-historia-cantidad]');
    this.porcentaje = document.querySelector('[data-historia-porcentaje]');
    this.progreso = document.querySelector('[data-historia-progreso]');
    this.imagenDetalle = document.querySelector('[data-historia-imagen-detalle]');
    this.galeria = document.querySelector('[data-historia-galeria]');
    this.listaProductores = document.querySelector('[data-lista-productores]');
    this.tituloCompra = document.querySelector('[data-historia-shop-titulo]');
    this.lotes = document.querySelector('[data-historia-lotes]');
  }

  iniciar() {
    if (!window.EstadoSurcos || !window.GruposCompraSurcos) {
      return;
    }

    this.productores = window.EstadoSurcos.obtenerColeccion('productores');
    this.grupos = window.EstadoSurcos.obtenerColeccion('gruposCompra')
      .map((grupo) => window.GruposCompraSurcos.completarGrupo(grupo));
    this.productor = this.obtenerProductorActivo();
    this.gruposProductor = this.grupos.filter((grupo) => grupo.productorId === this.productor.id);
    this.grupoPrincipal = this.gruposProductor.find((grupo) => grupo.estado === 'activo') || this.gruposProductor[0] || this.grupos[0];

    this.renderizar();
  }

  obtenerProductorActivo() {
    const parametros = new URLSearchParams(window.location.search);
    const productorId = parametros.get('productor');
    const grupoId = parametros.get('grupo') || parametros.get('id');
    const grupo = grupoId ? this.grupos.find((registro) => registro.id === grupoId) : null;

    return this.productores.find((productor) => productor.id === productorId)
      || this.productores.find((productor) => productor.id === grupo?.productorId)
      || this.productores.find((productor) => this.grupos.some((registro) => registro.productorId === productor.id))
      || this.productores[0];
  }

  renderizar() {
    if (!this.productor || !this.grupoPrincipal) {
      return;
    }

    const porcentaje = window.GruposCompraSurcos.calcularPorcentaje(this.grupoPrincipal);
    const nombreCorto = this.productor.nombre.replace(/^Finca\s+/i, '');

    this.escribirHtml(this.titulo, this.crearTitulo(nombreCorto));
    this.escribir(this.volumen, `Volumen ${this.productor.provincia}: ${this.productor.especialidad}`);
    this.escribir(this.responsable, this.productor.responsable);
    this.escribirHtml(this.ubicacion, `${this.productor.zona}, ${this.productor.provincia}<br>${this.grupoPrincipal.nodoRetiro}`);
    this.escribir(this.varietal, `${this.grupoPrincipal.producto} - ${this.grupoPrincipal.variedad}`);
    this.escribir(this.cosecha, `${window.FormatoSurcos.numero(this.grupoPrincipal.cantidadMinima * this.grupoPrincipal.personasObjetivo)} ${this.grupoPrincipal.unidad}`);
    this.escribir(this.asignacion, `${window.FormatoSurcos.numero(this.grupoPrincipal.cantidadMinima)} ${this.grupoPrincipal.unidad}`);
    this.escribir(this.cantidad, `${this.grupoPrincipal.personasActuales}/${this.grupoPrincipal.personasObjetivo} personas`);
    this.escribir(this.porcentaje, `${porcentaje}%`);

    if (this.progreso) {
      this.progreso.style.width = `${porcentaje}%`;
    }

    this.actualizarImagen(this.imagenHero, this.grupoPrincipal.imagen, `${this.grupoPrincipal.producto} ${this.grupoPrincipal.origen}`);
    this.actualizarImagen(this.imagenDetalle, this.grupoPrincipal.imagen, `${this.grupoPrincipal.producto} publicado en Surcos`);
    this.renderizarArticulo();
    this.renderizarGaleria();
    this.renderizarProductores();
    this.renderizarLotes();
  }

  renderizarArticulo() {
    if (!this.articulo) {
      return;
    }

    this.articulo.innerHTML = `
      <p class="dropcap">${this.productor.historia} Desde ${this.productor.zona}, ${this.productor.responsable} coordina lotes que llegan al comprador sin depender de una cadena larga de intermediarios.</p>
      <p>La publicacion activa de ${this.grupoPrincipal.producto} permite ver precio grupal, avance del pool, nodo de retiro y fecha estimada antes de comprometerse.</p>
      <p>Surcos convierte cada lote en una decision transparente: el productor define el volumen, el comprador se suma al grupo y el terminal mantiene el estado del compromiso visible.</p>
      <blockquote class="bq">
        <p>"Cada pool es una forma de vender con trazabilidad y con una comunidad que entiende de donde viene el producto."</p>
      </blockquote>
      <p>Cuando el volumen minimo se alcanza, el lote queda listo para coordinar entrega. Si el comprador cambia de decision, puede salir del pool y el historial conserva un solo registro actualizado.</p>
    `;
  }

  renderizarGaleria() {
    if (!this.galeria) {
      return;
    }

    const imagenes = this.obtenerImagenesGaleria();
    this.galeria.innerHTML = imagenes.map((imagen) => `
      <figure>
        <img alt="${imagen.texto}" src="${imagen.direccion}" />
        <figcaption>${imagen.texto}</figcaption>
      </figure>
    `).join('');
  }

  renderizarProductores() {
    if (!this.listaProductores) {
      return;
    }

    this.listaProductores.innerHTML = `
      <div class="productores-relacionados-inner">
        <h2>Mas Historias</h2>
        <div class="productores-relacionados-grid">
          ${this.productores.map((productor) => this.crearProductor(productor)).join('')}
        </div>
      </div>
    `;
  }

  crearProductor(productor) {
    const activo = productor.id === this.productor.id ? ' activo' : '';
    return `
      <a class="productor-relacionado${activo}" href="historias_productor.html?productor=${productor.id}">
        <span>${productor.provincia}</span>
        <strong>${productor.nombre}</strong>
        <small>${productor.especialidad}</small>
      </a>
    `;
  }

  renderizarLotes() {
    if (!this.lotes) {
      return;
    }

    if (this.tituloCompra) {
      this.tituloCompra.textContent = `Compra de ${this.productor.nombre}`;
    }

    const grupos = this.gruposProductor.filter((grupo) => grupo.estado === 'activo');
    this.lotes.innerHTML = grupos.length
      ? grupos.map((grupo) => this.crearTarjetaLote(grupo)).join('')
      : '<p class="historia-vacia">Este productor no tiene pools activos en este momento.</p>';
  }

  crearTarjetaLote(grupo) {
    return `
      <article class="shop-card">
        <figure>
          <img alt="${grupo.producto} ${grupo.variedad}" src="${grupo.imagen}" />
        </figure>
        <div class="body">
          <h3 class="sname">${grupo.producto}</h3>
          <p class="lot">${grupo.variedad} - ${grupo.origen}</p>
          <div class="sprice tab">${window.FormatoSurcos.moneda(grupo.precioGrupal)}</div>
          <a class="sbtn" href="pool_detail.html?id=${grupo.id}">Ver Pool</a>
        </div>
      </article>
    `;
  }

  obtenerImagenesGaleria() {
    return [
      {
        direccion: this.grupoPrincipal.imagen,
        texto: `${this.grupoPrincipal.producto} - ${this.productor.nombre}`
      },
      {
        direccion: 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=800&q=80&fit=crop',
        texto: `Seleccion manual - ${this.productor.zona}`
      },
      {
        direccion: 'https://images.unsplash.com/photo-1501854140801-50d01698950b?w=800&q=80&fit=crop',
        texto: `Paisaje agricola - ${this.productor.provincia}`
      }
    ];
  }

  crearTitulo(nombre) {
    const palabras = nombre.split(/\s+/);
    const primera = palabras.slice(0, Math.ceil(palabras.length / 2)).join(' ');
    const segunda = palabras.slice(Math.ceil(palabras.length / 2)).join(' ');
    return segunda ? `${primera}<br>${segunda}.` : `${primera}.`;
  }

  actualizarImagen(imagen, direccion, texto) {
    if (!imagen) {
      return;
    }

    imagen.src = direccion;
    imagen.alt = texto;
  }

  escribir(elemento, texto) {
    if (elemento) {
      elemento.textContent = texto;
    }
  }

  escribirHtml(elemento, texto) {
    if (elemento) {
      elemento.innerHTML = texto;
    }
  }
}

new HistoriasProductor().iniciar();

class DetalleGrupoCompra {
  constructor() {
    this.grupo = null;
    this.boton = document.querySelector('[data-accion-grupo="unirse"]');
    this.mensaje = document.querySelector('[data-mensaje-grupo]');
  }

  iniciar() {
    this.grupo = window.GruposCompraSurcos.obtenerGrupoDesdeDireccion();

    if (!this.grupo) {
      this.mostrarMensaje('No hay grupos de compra disponibles.');
      return;
    }

    this.renderizar();
    this.registrarAcciones();
  }

  renderizar() {
    const grupo = this.grupo;
    const porcentaje = window.GruposCompraSurcos.calcularPorcentaje(grupo);

    this.escribir('[data-grupo-cosecha]', `Cosecha programada ${window.FormatoSurcos.fechaCorta(grupo.fechaEntrega)}`);
    this.escribir('[data-grupo-frase]', `"${grupo.productor.historia || 'Trazabilidad directa desde la finca.'}"`);
    this.escribir('[data-grupo-titulo]', `${grupo.producto} ${grupo.productor.nombre}`);
    this.escribir('[data-grupo-origen]', `Origen: ${grupo.origen}`);
    this.escribir('[data-grupo-variedad]', grupo.variedad);
    this.escribir('[data-grupo-fecha-limite]', window.FormatoSurcos.fechaCorta(grupo.fechaCierre));
    this.escribir('[data-grupo-hora]', 'Cierra: 11:59 PM');
    this.escribir('[data-grupo-cantidad]', `${grupo.personasActuales}/${grupo.personasObjetivo} personas`);
    this.escribir('[data-grupo-porcentaje]', `${porcentaje}%`);
    this.escribir('[data-grupo-precio-mercado]', `${window.FormatoSurcos.moneda(grupo.precioMercado)}`);
    this.escribir('[data-grupo-precio]', `${window.FormatoSurcos.moneda(grupo.precioGrupal)}`);
    this.escribir('[data-grupo-historia]', grupo.productor.historia || 'Producto publicado por productor verificado de Surcos.');
    this.escribir('[data-grupo-entrega]', window.FormatoSurcos.fechaCorta(grupo.fechaEntrega));
    this.escribir('[data-grupo-nodo]', grupo.nodoRetiro);
    this.escribir('[data-grupo-minimo]', `${grupo.cantidadMinima} ${grupo.unidad}`);
    this.escribir('[data-grupo-modelo-titulo]', grupo.modeloEntrega);
    this.escribir('[data-grupo-modelo-desc]', `${grupo.modeloEntrega} - ${grupo.nodoRetiro}`);

    document.querySelectorAll('[data-grupo-imagen]').forEach((imagen) => {
      imagen.src = grupo.imagen;
      imagen.alt = `${grupo.producto} ${grupo.origen}`;
    });

    document.querySelectorAll('[data-grupo-progreso]').forEach((barra) => {
      barra.style.setProperty('--pool-progress', `${porcentaje}%`);
      barra.style.width = `${porcentaje}%`;
    });

    this.actualizarBoton();
  }

  registrarAcciones() {
    if (!this.boton) {
      return;
    }

    this.boton.addEventListener('click', () => this.unirse());
  }

  unirse() {
    const resultado = window.GruposCompraSurcos.comprometerse(this.grupo.id);

    if (resultado.requiereIngreso) {
      const retorno = encodeURIComponent(`pool_detail.html?id=${this.grupo.id}`);
      window.location.href = `ingreso.html?retorno=${retorno}`;
      return;
    }

    this.mostrarMensaje(resultado.mensaje);

    if (resultado.exito) {
      this.grupo = resultado.grupo;
      this.renderizar();
      this.boton.textContent = 'Compromiso Registrado';
      this.boton.disabled = true;
    }
  }

  actualizarBoton() {
    if (!this.boton) {
      return;
    }

    const comprometido = window.GruposCompraSurcos.usuarioEstaComprometido(this.grupo.id);
    this.boton.disabled = comprometido;

    if (comprometido) {
      this.boton.textContent = 'Ya Comprometido';
    }
  }

  escribir(selector, texto) {
    document.querySelectorAll(selector).forEach((elemento) => {
      elemento.textContent = texto;
    });
  }

  mostrarMensaje(texto) {
    if (this.mensaje) {
      this.mensaje.textContent = texto;
      this.mensaje.hidden = false;
    }
  }
}

new DetalleGrupoCompra().iniciar();

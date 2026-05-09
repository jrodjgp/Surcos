class CajonLateral {
  constructor({
    idDisparador = 'drawerTrigger',
    idCajon = 'drawer',
    idCapa = 'drawerOverlay',
    idCerrar = 'drawerClose'
  } = {}) {
    this.disparador = document.getElementById(idDisparador);
    this.cajon = document.getElementById(idCajon);
    this.capa = document.getElementById(idCapa);
    this.cerrarBoton = document.getElementById(idCerrar);
  }

  iniciar() {
    if (!this.disparador || !this.cajon || !this.capa || !this.cerrarBoton) {
      return;
    }

    this.disparador.addEventListener('click', () => this.abrir());
    this.capa.addEventListener('click', () => this.cerrar());
    this.cerrarBoton.addEventListener('click', () => this.cerrar());
    document.addEventListener('keydown', (evento) => this.cerrarConEscape(evento));
  }

  abrir() {
    this.cajon.classList.add('open');
    this.capa.classList.add('open');
    this.disparador.style.display = 'none';
  }

  cerrar() {
    this.cajon.classList.remove('open');
    this.capa.classList.remove('open');
    this.disparador.style.display = '';
  }

  cerrarConEscape(evento) {
    if (evento.key === 'Escape' && this.cajon.classList.contains('open')) {
      this.cerrar();
    }
  }
}

window.CajonLateral = CajonLateral;

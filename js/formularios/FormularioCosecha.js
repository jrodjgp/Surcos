import { Cosecha } from '../modelos/Cosecha.js';

export class FormularioCosecha {
  constructor(formulario) {
    this.formulario = formulario;
    this.mensaje = document.getElementById('mensajeCosecha');
    this.boton = formulario.querySelector('[data-accion="publicar-cosecha"]');
  }

  iniciar() {
    this.formulario.addEventListener('submit', (evento) => this.manejarEnvio(evento));
    this.formulario.addEventListener('input', () => this.actualizarEstadoBoton());
    this.actualizarEstadoBoton();
  }

  manejarEnvio(evento) {
    evento.preventDefault();
    evento.stopPropagation();

    this.formulario.classList.add('was-validated');

    if (!this.formulario.checkValidity()) {
      this.mostrarMensaje('Revisa los campos marcados antes de publicar la cosecha.', false);
      return;
    }

    const cosecha = this.crearCosecha();
    this.mostrarMensaje(cosecha.crearMensajeConfirmacion(), true);
    this.formulario.reset();
    this.formulario.classList.remove('was-validated');
    this.actualizarEstadoBoton();
  }

  crearCosecha() {
    const datos = new FormData(this.formulario);

    return new Cosecha({
      producto: datos.get('producto'),
      variedad: datos.get('variedad'),
      cantidadKg: datos.get('cantidadKg'),
      precioMinimo: datos.get('precioMinimo'),
      ubicacion: datos.get('ubicacion'),
      ventana: datos.get('ventana'),
      modeloEntrega: datos.get('modeloEntrega')
    });
  }

  actualizarEstadoBoton() {
    this.boton.disabled = !this.formulario.checkValidity();
  }

  mostrarMensaje(texto, esExito) {
    this.mensaje.textContent = texto;
    this.mensaje.hidden = false;
    this.mensaje.classList.toggle('alert-success', esExito);
    this.mensaje.classList.toggle('alert-danger', !esExito);
  }
}


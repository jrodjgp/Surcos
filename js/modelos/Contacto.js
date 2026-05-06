class Contacto {
  constructor({
    nombre,
    correo,
    telefono,
    tipoUsuario,
    asunto,
    mensaje
  }) {
    this.nombre = nombre.trim();
    this.correo = correo.trim();
    this.telefono = telefono.trim();
    this.tipoUsuario = tipoUsuario;
    this.asunto = asunto.trim();
    this.mensaje = mensaje.trim();
  }

  get iniciales() {
    return this.nombre
      .split(' ')
      .filter(Boolean)
      .slice(0, 2)
      .map((parte) => parte[0].toUpperCase())
      .join('');
  }

  get resumen() {
    return `${this.nombre} (${this.tipoUsuario}) escribio sobre ${this.asunto}.`;
  }

  crearMensajeConfirmacion() {
    return `Mensaje recibido: ${this.resumen} El equipo Surcos respondera a ${this.correo}.`;
  }
}

window.Contacto = Contacto;


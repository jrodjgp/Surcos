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

  crearRegistro() {
    const crearId = window.FormatoSurcos?.crearId
      || ((prefijo) => `${prefijo}-${Date.now().toString(36)}`);

    return {
      id: crearId('msg', `${this.nombre} ${this.asunto}`),
      nombre: this.nombre,
      correo: this.correo,
      telefono: this.telefono,
      tipoUsuario: this.tipoUsuario,
      asunto: this.asunto,
      mensaje: this.mensaje,
      estado: 'recibido',
      fecha: new Date().toISOString().slice(0, 10)
    };
  }

  crearMensajeConfirmacion() {
    return `Mensaje recibido: ${this.resumen} El equipo Surcos respondera a ${this.correo}.`;
  }
}

window.Contacto = Contacto;


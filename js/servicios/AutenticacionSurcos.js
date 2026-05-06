class AutenticacionSurcos {
  static claveSesion = 'surcos.sesion.v1';

  static normalizarCorreo(correo) {
    return String(correo || '').trim().toLowerCase();
  }

  static crearIniciales(nombre) {
    return String(nombre || 'Usuario Surcos')
      .trim()
      .split(/\s+/)
      .slice(0, 2)
      .map((parte) => parte.charAt(0).toUpperCase())
      .join('') || 'US';
  }

  static obtenerUsuarioActual() {
    const sesion = this.obtenerSesion();

    if (!sesion?.usuarioId) {
      return null;
    }

    const estado = window.EstadoSurcos.obtenerEstado();
    const usuarios = estado.usuarios || [estado.usuarioDemo].filter(Boolean);
    return usuarios.find((usuario) => usuario.id === sesion.usuarioId) || null;
  }

  static buscarPorCorreo(correo) {
    const correoNormalizado = this.normalizarCorreo(correo);
    const usuarios = window.EstadoSurcos.obtenerColeccion('usuarios');

    return usuarios.find((usuario) => this.normalizarCorreo(usuario.correo) === correoNormalizado) || null;
  }

  static validarCredenciales(correo, clave) {
    const usuario = this.buscarPorCorreo(correo);

    if (!usuario || usuario.clave !== clave) {
      return null;
    }

    return usuario;
  }

  static registrarUsuario(datos) {
    const correo = this.normalizarCorreo(datos.correo);
    const existente = this.buscarPorCorreo(correo);

    if (existente) {
      return { exito: false, mensaje: 'Ya existe una cuenta con ese correo.' };
    }

    const nombre = String(datos.nombre || '').trim();
    const usuario = {
      id: window.FormatoSurcos.crearId('usr', `${nombre || 'usuario'} ${correo}`),
      nombre,
      correo,
      clave: datos.clave,
      telefono: String(datos.telefono || '').trim(),
      rol: datos.rol || 'comprador',
      provincia: datos.provincia || 'Panama',
      nodoRetiro: datos.nodoRetiro || 'PTY Terminal Oeste',
      iniciales: this.crearIniciales(nombre)
    };

    window.EstadoSurcos.agregar('usuarios', usuario);
    this.iniciarSesion(usuario);

    return { exito: true, usuario };
  }

  static iniciarSesion(usuario) {
    localStorage.setItem(this.claveSesion, JSON.stringify({
      usuarioId: usuario.id,
      fechaIngreso: new Date().toISOString()
    }));

    return usuario;
  }

  static cerrarSesion() {
    localStorage.removeItem(this.claveSesion);
  }

  static obtenerSesion() {
    try {
      const texto = localStorage.getItem(this.claveSesion);
      return texto ? JSON.parse(texto) : null;
    } catch (excepcion) {
      console.warn('No se pudo leer la sesion local de Surcos.', excepcion);
      return null;
    }
  }

  static haySesionActiva() {
    return Boolean(this.obtenerUsuarioActual());
  }
}

window.AutenticacionSurcos = AutenticacionSurcos;

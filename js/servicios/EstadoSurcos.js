class EstadoSurcos {
  static clave = 'surcos.estado.v1';

  static obtenerEstado() {
    const guardado = this.leerGuardado();
    const semillas = this.clonar(window.DatosInicialesSurcos || {});

    if (!guardado) {
      this.guardarEstado(semillas);
      return semillas;
    }

    return this.fusionarEstado(semillas, guardado);
  }

  static guardarEstado(estado) {
    localStorage.setItem(this.clave, JSON.stringify(estado));
    return estado;
  }

  static reiniciar() {
    localStorage.removeItem(this.clave);
    return this.obtenerEstado();
  }

  static obtenerColeccion(nombre) {
    return this.obtenerEstado()[nombre] || [];
  }

  static guardarColeccion(nombre, coleccion) {
    const estado = this.obtenerEstado();
    estado[nombre] = coleccion;
    this.guardarEstado(estado);
    return coleccion;
  }

  static buscarPorId(nombre, id) {
    return this.obtenerColeccion(nombre).find((elemento) => elemento.id === id) || null;
  }

  static agregar(nombre, registro) {
    const coleccion = this.obtenerColeccion(nombre);
    const actualizada = [...coleccion, registro];
    this.guardarColeccion(nombre, actualizada);
    return registro;
  }

  static actualizar(nombre, id, cambios) {
    const coleccion = this.obtenerColeccion(nombre);
    const actualizada = coleccion.map((elemento) => (
      elemento.id === id ? { ...elemento, ...cambios } : elemento
    ));
    this.guardarColeccion(nombre, actualizada);
    return this.buscarPorId(nombre, id);
  }

  static eliminar(nombre, id) {
    const coleccion = this.obtenerColeccion(nombre);
    const actualizada = coleccion.filter((elemento) => elemento.id !== id);
    this.guardarColeccion(nombre, actualizada);
    return actualizada.length !== coleccion.length;
  }

  static leerGuardado() {
    try {
      const texto = localStorage.getItem(this.clave);
      return texto ? JSON.parse(texto) : null;
    } catch (excepcion) {
      console.warn('No se pudo leer el estado local de Surcos.', excepcion);
      return null;
    }
  }

  static fusionarEstado(semillas, guardado) {
    return {
      ...semillas,
      ...guardado,
      version: semillas.version || guardado.version,
      usuarioDemo: this.fusionarUsuarioSemilla(semillas.usuarioDemo, guardado.usuarioDemo),
      usuarios: this.fusionarUsuarios(semillas.usuarios || [], guardado.usuarios || []),
      configuracion: this.fusionarConfiguracion(semillas.configuracion || {}, guardado.configuracion || {})
    };
  }

  static fusionarUsuarios(semillas, guardados) {
    const usuarios = [...guardados];

    semillas.forEach((semilla) => {
      const indice = usuarios.findIndex((usuario) => usuario.id === semilla.id);

      if (indice >= 0) {
        usuarios[indice] = this.fusionarUsuarioSemilla(semilla, usuarios[indice]);
        return;
      }

      usuarios.push(semilla);
    });

    return usuarios;
  }

  static fusionarUsuarioSemilla(semilla, guardado = {}) {
    const esUsuarioAnterior = guardado.id === 'usr-nodo-panama' && guardado.nombre === 'Nodo Panama';

    if (esUsuarioAnterior) {
      return { ...guardado, ...semilla };
    }

    return { ...semilla, ...guardado };
  }

  static fusionarConfiguracion(semilla, guardado) {
    return {
      ...semilla,
      ...guardado,
      notificaciones: {
        ...(semilla.notificaciones || {}),
        ...(guardado.notificaciones || {})
      }
    };
  }

  static clonar(valor) {
    return JSON.parse(JSON.stringify(valor));
  }
}

window.EstadoSurcos = EstadoSurcos;

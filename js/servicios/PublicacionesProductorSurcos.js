class PublicacionesProductorSurcos {
  static publicar(cosecha) {
    const estado = window.EstadoSurcos.obtenerEstado();
    const usuario = window.AutenticacionSurcos.obtenerUsuarioActual() || estado.usuarioDemo;
    const productor = this.obtenerProductor(usuario, cosecha);
    const configuracion = estado.configuracion || {};
    const grupo = cosecha.crearGrupoCompra({
      productorId: productor.id,
      nodoRetiro: configuracion.nodoRetiro || usuario.nodoRetiro || 'PTY Terminal Oeste',
      imagen: this.obtenerImagen(cosecha.producto)
    });

    window.EstadoSurcos.agregar('gruposCompra', grupo);
    window.EstadoSurcos.agregar('actividad', {
      id: window.FormatoSurcos.crearId('act', `${grupo.id} publicado`),
      tipo: 'cosecha',
      texto: `Publicaste ${grupo.producto} ${grupo.variedad} en el marketplace`,
      fecha: new Date().toISOString().slice(0, 10)
    });

    return {
      exito: true,
      mensaje: `${grupo.producto} ${grupo.variedad} ya esta visible como pool activo.`,
      grupo,
      productor
    };
  }

  static obtenerProductor(usuario, cosecha) {
    const productores = window.EstadoSurcos.obtenerColeccion('productores');
    const productorExistente = productores.find((productor) => productor.usuarioId === usuario.id);
    const provincia = this.extraerProvincia(cosecha.ubicacion, usuario.provincia);
    const zona = this.extraerZona(cosecha.ubicacion);
    const cambios = {
      usuarioId: usuario.id,
      nombre: productorExistente?.nombre || `Finca ${usuario.nombre}`,
      responsable: usuario.nombre,
      provincia,
      zona,
      especialidad: cosecha.producto,
      historia: `Productor registrado en Surcos con lote activo de ${cosecha.producto}.`
    };

    if (productorExistente) {
      return window.EstadoSurcos.actualizar('productores', productorExistente.id, cambios);
    }

    const productor = {
      id: `prod-${usuario.id}`,
      ...cambios
    };

    window.EstadoSurcos.agregar('productores', productor);
    return productor;
  }

  static obtenerGruposProductorActual() {
    return this.obtenerGruposProductor()
      .filter((grupo) => grupo.estado === 'activo');
  }

  static obtenerGruposProductor() {
    const estado = window.EstadoSurcos.obtenerEstado();
    const usuario = window.AutenticacionSurcos.obtenerUsuarioActual() || estado.usuarioDemo;
    const productor = this.obtenerProductorActual(usuario);

    if (!productor) {
      return [];
    }

    return window.EstadoSurcos.obtenerColeccion('gruposCompra')
      .filter((grupo) => grupo.productorId === productor.id)
      .map((grupo) => window.GruposCompraSurcos.completarGrupo(grupo));
  }

  static obtenerProductorActual(usuario) {
    return window.EstadoSurcos.obtenerColeccion('productores')
      .find((registro) => registro.usuarioId === usuario.id) || null;
  }

  static obtenerImagen(producto) {
    const productoNormalizado = String(producto || '').toLowerCase();
    const imagenes = [
      {
        clave: 'tomate',
        direccion: 'https://images.unsplash.com/photo-1592924357228-91a4daadcfea?w=800&q=80&fit=crop'
      },
      {
        clave: 'cafe',
        direccion: 'https://images.unsplash.com/photo-1447933601403-0c6688de566e?w=800&q=80&fit=crop'
      },
      {
        clave: 'miel',
        direccion: 'https://images.unsplash.com/photo-1558642452-9d2a7deb7f62?w=800&q=80&fit=crop'
      },
      {
        clave: 'cacao',
        direccion: 'https://images.unsplash.com/photo-1606313564200-e75d5e30476c?w=800&q=80&fit=crop'
      }
    ];
    const coincidencia = imagenes.find((imagen) => productoNormalizado.includes(imagen.clave));

    return coincidencia?.direccion || 'https://images.unsplash.com/photo-1498579397066-22750a3cb424?w=800&q=80&fit=crop';
  }

  static extraerProvincia(ubicacion, respaldo) {
    const partes = String(ubicacion || '').split(',').map((parte) => parte.trim()).filter(Boolean);
    return partes[1] || respaldo || partes[0] || 'Panama';
  }

  static extraerZona(ubicacion) {
    return String(ubicacion || '').split(',')[0].trim() || 'Finca registrada';
  }
}

window.PublicacionesProductorSurcos = PublicacionesProductorSurcos;

const DatosInicialesSurcos = {
  version: 2,
  usuarioDemo: {
    id: 'usr-nodo-panama',
    nombre: 'Juan Juanes',
    correo: 'nodo@surcos.pa',
    clave: 'surcos2026',
    telefono: '+507 6000-0000',
    rol: 'comprador',
    provincia: 'Panama',
    nodoRetiro: 'PTY Terminal Oeste',
    iniciales: 'JJ'
  },
  usuarios: [
    {
      id: 'usr-nodo-panama',
      nombre: 'Juan Juanes',
      correo: 'nodo@surcos.pa',
      clave: 'surcos2026',
      telefono: '+507 6000-0000',
      rol: 'comprador',
      provincia: 'Panama',
      nodoRetiro: 'PTY Terminal Oeste',
      iniciales: 'JJ'
    }
  ],
  productores: [
    {
      id: 'prod-heredia',
      nombre: 'Finca Heredia',
      responsable: 'Don Sebastian Heredia',
      provincia: 'Chiriqui',
      zona: 'Boquete',
      especialidad: 'Cafe Geisha',
      historia: 'Productor de micro-lotes de altura con proceso honey y perfiles florales.'
    },
    {
      id: 'prod-oasis',
      nombre: 'Finca Oasis',
      responsable: 'Ana Rodriguez',
      provincia: 'Chiriqui',
      zona: 'Tierras Altas',
      especialidad: 'Tomates de Herencia',
      historia: 'Cultivo de hortalizas de temporada en suelos volcanicos y clima frio.'
    },
    {
      id: 'prod-bosque',
      nombre: 'Apiario Bosque Silvestre',
      responsable: 'Luis Medina',
      provincia: 'Cocle',
      zona: 'El Valle',
      especialidad: 'Miel Cruda',
      historia: 'Apiario artesanal con cosechas pequenas y trazabilidad por floracion.'
    },
    {
      id: 'prod-darien',
      nombre: 'Cooperativa Darien Cacao',
      responsable: 'Marta Quintero',
      provincia: 'Darien',
      zona: 'Meteti',
      especialidad: 'Cacao Crudo',
      historia: 'Cooperativa familiar enfocada en cacao fermentado de origen unico.'
    },
    {
      id: 'prod-azuero',
      nombre: 'Finca Azuero Verde',
      responsable: 'Carlos Batista',
      provincia: 'Herrera',
      zona: 'Chitre',
      especialidad: 'Aceite Arbequina',
      historia: 'Produccion limitada de aceite prensado en frio para pedidos grupales.'
    }
  ],
  gruposCompra: [
    {
      id: 'grupo-geisha-42',
      productorId: 'prod-heredia',
      producto: 'Cafe Geisha',
      variedad: 'Micro-lote #42',
      categoria: 'cafe',
      origen: 'Tierras Altas, Chiriqui',
      imagen: 'https://images.unsplash.com/photo-1447933601403-0c6688de566e?w=800&q=80&fit=crop',
      precioMercado: 1.1,
      precioGrupal: 0.62,
      unidad: 'lb',
      personasActuales: 17,
      personasObjetivo: 20,
      cantidadMinima: 2,
      fechaCierre: '2026-05-18T23:59:00-05:00',
      fechaEntrega: '2026-05-30',
      estado: 'activo',
      modeloEntrega: 'Retiro en Nodo',
      nodoRetiro: 'PTY Terminal Oeste'
    },
    {
      id: 'grupo-geisha-04-historico',
      productorId: 'prod-heredia',
      producto: 'Cafe Geisha',
      variedad: 'Honey #04',
      categoria: 'cafe',
      origen: 'Boquete, Chiriqui',
      imagen: 'https://images.unsplash.com/photo-1447933601403-0c6688de566e?w=800&q=80&fit=crop',
      precioMercado: 1.1,
      precioGrupal: 0.62,
      unidad: 'lb',
      personasActuales: 20,
      personasObjetivo: 20,
      cantidadMinima: 2,
      fechaCierre: '2026-04-10T23:59:00-05:00',
      fechaEntrega: '2026-04-12',
      estado: 'cerrado',
      modeloEntrega: 'Retiro en Nodo',
      nodoRetiro: 'PTY Terminal Oeste'
    },
    {
      id: 'grupo-tomates-09',
      productorId: 'prod-oasis',
      producto: 'Tomates de Herencia',
      variedad: 'Lote 09',
      categoria: 'hortalizas',
      origen: 'Boquete, Chiriqui',
      imagen: 'https://images.unsplash.com/photo-1592924357228-91a4daadcfea?w=800&q=80&fit=crop',
      precioMercado: 0.85,
      precioGrupal: 0.45,
      unidad: 'lb',
      personasActuales: 21,
      personasObjetivo: 50,
      cantidadMinima: 5,
      fechaCierre: '2026-05-15T23:59:00-05:00',
      fechaEntrega: '2026-05-30',
      estado: 'activo',
      modeloEntrega: 'Retiro en Nodo',
      nodoRetiro: 'Mercado Central'
    },
    {
      id: 'grupo-miel-cruda',
      productorId: 'prod-bosque',
      producto: 'Miel Silvestre Artesanal',
      variedad: 'Cruda',
      categoria: 'miel',
      origen: 'El Valle, Cocle',
      imagen: 'https://images.unsplash.com/photo-1558642452-9d2a7deb7f62?w=800&q=80&fit=crop',
      precioMercado: 8.5,
      precioGrupal: 4.2,
      unidad: 'lb',
      personasActuales: 49,
      personasObjetivo: 50,
      cantidadMinima: 1,
      fechaCierre: '2026-05-20T23:59:00-05:00',
      fechaEntrega: '2026-06-03',
      estado: 'activo',
      modeloEntrega: 'Envio a Domicilio',
      nodoRetiro: 'Nodo Penonome'
    },
    {
      id: 'grupo-miel-01-historico',
      productorId: 'prod-bosque',
      producto: 'Miel Silvestre Artesanal',
      variedad: 'Cruda',
      categoria: 'miel',
      origen: 'El Valle, Cocle',
      imagen: 'https://images.unsplash.com/photo-1558642452-9d2a7deb7f62?w=800&q=80&fit=crop',
      precioMercado: 8.5,
      precioGrupal: 4.2,
      unidad: 'lb',
      personasActuales: 50,
      personasObjetivo: 50,
      cantidadMinima: 1,
      fechaCierre: '2026-04-02T23:59:00-05:00',
      fechaEntrega: '2026-04-04',
      estado: 'cerrado',
      modeloEntrega: 'Envio a Domicilio',
      nodoRetiro: 'Nodo Penonome'
    },
    {
      id: 'grupo-cacao-07',
      productorId: 'prod-darien',
      producto: 'Cacao Crudo',
      variedad: 'Lote 7',
      categoria: 'cacao',
      origen: 'Meteti, Darien',
      imagen: 'https://images.unsplash.com/photo-1606313564200-e75d5e30476c?w=800&q=80&fit=crop',
      precioMercado: 3.9,
      precioGrupal: 2.35,
      unidad: 'lb',
      personasActuales: 12,
      personasObjetivo: 35,
      cantidadMinima: 3,
      fechaCierre: '2026-05-22T23:59:00-05:00',
      fechaEntrega: '2026-06-05',
      estado: 'activo',
      modeloEntrega: 'Retiro en Nodo',
      nodoRetiro: 'PTY Terminal Oeste'
    },
    {
      id: 'grupo-arbequina-azuero',
      productorId: 'prod-azuero',
      producto: 'Aceite Arbequina',
      variedad: 'Prensado en frio',
      categoria: 'aceite',
      origen: 'Chitre, Herrera',
      imagen: 'https://images.unsplash.com/photo-1474979266404-7eaacbcd87c5?w=800&q=80&fit=crop',
      precioMercado: 14.5,
      precioGrupal: 9.75,
      unidad: 'botella',
      personasActuales: 8,
      personasObjetivo: 25,
      cantidadMinima: 1,
      fechaCierre: '2026-05-28T23:59:00-05:00',
      fechaEntrega: '2026-06-10',
      estado: 'activo',
      modeloEntrega: 'Lote Empresarial',
      nodoRetiro: 'Nodo Chitre Norte'
    }
  ],
  ordenes: [
    {
      id: 'ord-geisha-04',
      grupoCompraId: 'grupo-geisha-04-historico',
      usuarioId: 'usr-nodo-panama',
      producto: 'Geisha Honey #04',
      origen: 'Boquete',
      monto: 218,
      estadoGrupo: 'ganado',
      estadoEntrega: 'entregado',
      fecha: '2026-04-12'
    },
    {
      id: 'ord-miel-01',
      grupoCompraId: 'grupo-miel-01-historico',
      usuarioId: 'usr-nodo-panama',
      producto: 'Miel Silvestre Artesanal',
      origen: 'El Valle, Cocle',
      monto: 96.5,
      estadoGrupo: 'ganado',
      estadoEntrega: 'entregado',
      fecha: '2026-04-04'
    },
    {
      id: 'ord-cacao-07',
      grupoCompraId: 'grupo-cacao-07',
      usuarioId: 'usr-nodo-panama',
      producto: 'Cacao Crudo Lote 7',
      origen: 'Darien',
      monto: 133,
      estadoGrupo: 'pendiente',
      estadoEntrega: 'transito',
      fecha: '2026-03-29'
    }
  ],
  metodosPago: [
    {
      id: 'pago-visa-4821',
      tipo: 'VISA',
      etiqueta: 'Tarjeta principal',
      ultimos: '4821',
      principal: true
    },
    {
      id: 'pago-mastercard-0934',
      tipo: 'MASTERCARD',
      etiqueta: 'Tarjeta respaldo',
      ultimos: '0934',
      principal: false
    },
    {
      id: 'pago-yappy',
      tipo: 'YAPPY PA',
      etiqueta: 'Yappy Panama',
      ultimos: 'Yappy',
      principal: false
    }
  ],
  configuracion: {
    idioma: 'ES',
    unidadDatos: 'metrico',
    umbralCompromiso: 60,
    provinciaRetiro: 'Panama',
    nodoRetiro: 'PTY Terminal Oeste',
    notificaciones: {
      alertasPool: true,
      actualizacionesEntrega: true,
      bajasPrecio: false
    }
  },
  nodosRetiro: [
    { provincia: 'Chiriqui', nodo: 'Nodo David Centro' },
    { provincia: 'Herrera', nodo: 'Nodo Chitre Norte' },
    { provincia: 'Los Santos', nodo: 'Nodo Las Tablas' },
    { provincia: 'Cocle', nodo: 'Nodo Penonome' },
    { provincia: 'Panama', nodo: 'PTY Terminal Oeste' },
    { provincia: 'Colon', nodo: 'Nodo Colon Puerto' },
    { provincia: 'Darien', nodo: 'Nodo Meteti' }
  ],
  actividad: [
    {
      id: 'act-geisha',
      tipo: 'grupo',
      texto: 'Comprometido al grupo Cafe Geisha Micro-lote #42',
      fecha: '2026-04-12'
    },
    {
      id: 'act-perfil',
      tipo: 'perfil',
      texto: 'Preferencias de notificacion actualizadas',
      fecha: '2026-04-10'
    }
  ]
};

window.DatosInicialesSurcos = DatosInicialesSurcos;

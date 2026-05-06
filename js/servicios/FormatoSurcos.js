class FormatoSurcos {
  static moneda(valor, moneda = 'PAB') {
    return new Intl.NumberFormat('es-PA', {
      style: 'currency',
      currency: moneda,
      minimumFractionDigits: 2
    }).format(Number(valor) || 0);
  }

  static numero(valor) {
    return new Intl.NumberFormat('es-PA').format(Number(valor) || 0);
  }

  static porcentaje(actual, objetivo) {
    if (!objetivo) {
      return 0;
    }

    return Math.min(100, Math.round((Number(actual) / Number(objetivo)) * 100));
  }

  static fechaCorta(fechaIso) {
    if (!fechaIso) {
      return '';
    }

    return new Intl.DateTimeFormat('es-PA', {
      day: '2-digit',
      month: 'short',
      year: 'numeric'
    }).format(new Date(fechaIso));
  }

  static crearId(prefijo, texto = '') {
    const base = texto
      .toString()
      .normalize('NFD')
      .replace(/[\u0300-\u036f]/g, '')
      .toLowerCase()
      .replace(/[^a-z0-9]+/g, '-')
      .replace(/(^-|-$)/g, '');
    const marca = Date.now().toString(36);

    return `${prefijo}-${base || 'registro'}-${marca}`;
  }
}

window.FormatoSurcos = FormatoSurcos;

class ConfiguracionTerminal {
  constructor() {
    this.rangoUmbral = document.getElementById('umbralCompromiso');
    this.textoUmbral = document.getElementById('valorUmbral');
    this.radiosUnidad = document.querySelectorAll('input[name="unidadDatos"]');
    this.valorVista = document.getElementById('vistaVolumen');
    this.notaVista = document.getElementById('notaUnidad');
  }

  iniciar() {
    if (this.rangoUmbral && this.textoUmbral) {
      this.rangoUmbral.addEventListener('input', () => this.actualizarUmbral());
      this.actualizarUmbral();
    }

    this.radiosUnidad.forEach((radio) => {
      radio.addEventListener('change', () => this.actualizarUnidad());
    });
    this.actualizarUnidad();
  }

  actualizarUmbral() {
    const valor = Number(this.rangoUmbral.value);
    this.textoUmbral.textContent = `${valor}%`;
    this.rangoUmbral.style.setProperty('--valor-rango', `${valor}%`);
  }

  actualizarUnidad() {
    const unidadActiva = document.querySelector('input[name="unidadDatos"]:checked')?.value || 'metrico';
    const esImperial = unidadActiva === 'imperial';
    const volumenKg = 1240;
    const volumen = esImperial ? Math.round(volumenKg * 2.20462).toLocaleString('es-PA') : volumenKg.toLocaleString('es-PA');
    const unidad = esImperial ? 'lb' : 'kg';

    if (this.valorVista) {
      this.valorVista.textContent = `${volumen} ${unidad}`;
    }

    if (this.notaVista) {
      this.notaVista.textContent = esImperial
        ? 'Los volumenes del terminal se mostraran en libras.'
        : 'Los volumenes del terminal se mostraran en kilogramos.';
    }
  }
}

new ConfiguracionTerminal().iniciar();

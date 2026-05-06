import { FormularioCosecha } from './formularios/FormularioCosecha.js';

const formularioCosecha = document.getElementById('formularioCosecha');
const disparadorCajon = document.getElementById('drawerTrigger');
const cajon = document.getElementById('drawer');
const capaCajon = document.getElementById('drawerOverlay');
const cierreCajon = document.getElementById('drawerClose');

if (formularioCosecha) {
  new FormularioCosecha(formularioCosecha).iniciar();
}

if (disparadorCajon && cajon && capaCajon && cierreCajon) {
  const cerrarCajon = () => {
    cajon.classList.remove('open');
    capaCajon.classList.remove('open');
    disparadorCajon.style.display = '';
  };

  disparadorCajon.addEventListener('click', () => {
    cajon.classList.add('open');
    capaCajon.classList.add('open');
    disparadorCajon.style.display = 'none';
  });

  capaCajon.addEventListener('click', cerrarCajon);
  cierreCajon.addEventListener('click', cerrarCajon);
}


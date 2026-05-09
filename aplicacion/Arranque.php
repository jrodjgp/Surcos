<?php

declare(strict_types=1);

define('RUTA_RAIZ', dirname(__DIR__));
define('RUTA_APLICACION', RUTA_RAIZ . '/aplicacion');
define('RUTA_PUBLICA', RUTA_RAIZ . '/publico');

require_once RUTA_APLICACION . '/Soporte/ayudantes.php';
require_once RUTA_APLICACION . '/Soporte/Sesion.php';
require_once RUTA_APLICACION . '/Soporte/ProteccionCsrf.php';
require_once RUTA_APLICACION . '/Soporte/BaseDatos.php';

cargar_archivo_entorno(RUTA_RAIZ . '/.env');

$GLOBALS['configuracion_surcos'] = require RUTA_RAIZ . '/configuracion/aplicacion.php';

$entorno = (string) configuracion('aplicacion.entorno', 'local');
$depuracion = (bool) configuracion('aplicacion.depuracion', false);

date_default_timezone_set((string) configuracion('aplicacion.zona_horaria', 'America/Bogota'));

if ($depuracion) {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
    error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
}

Sesion::iniciar(configuracion(null, []));

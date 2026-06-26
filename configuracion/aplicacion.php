<?php

declare(strict_types=1);

$usaHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
    || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');

return [
    'aplicacion' => [
        'nombre' => valor_entorno('NOMBRE_APLICACION', 'Surcos'),
        'entorno' => valor_entorno('ENTORNO_APLICACION', 'local'),
        'depuracion' => valor_entorno('DEPURACION_APLICACION', false),
        'url' => valor_entorno('URL_APLICACION', ''),
        'zona_horaria' => valor_entorno('ZONA_HORARIA_APLICACION', 'America/Bogota'),
    ],
    'sesion' => [
        'nombre' => valor_entorno('NOMBRE_SESION', 'surcos_session'),
        'duracion' => valor_entorno('DURACION_SESION', 7200),
        'segura' => valor_entorno('SESION_SEGURA', $usaHttps),
        'ruta' => valor_entorno('RUTA_SESIONES', RUTA_RAIZ . '/almacenamiento/sesiones'),
    ],
    'base_datos' => [
        'motor' => valor_entorno('MOTOR_BASE_DATOS', 'mysql'),
        'url' => valor_entorno('URL_MYSQL', valor_entorno('MYSQL_URL', '')),
        'host' => valor_entorno('MYSQL_HOST', '127.0.0.1'),
        'puerto' => valor_entorno('MYSQL_PORT', 3306),
        'base' => valor_entorno('MYSQL_DATABASE', 'surcos'),
        'usuario' => valor_entorno('MYSQL_USER', 'root'),
        'clave' => valor_entorno('MYSQL_PASSWORD', ''),
        'charset' => valor_entorno('MYSQL_CHARSET', 'utf8mb4'),
    ],
];

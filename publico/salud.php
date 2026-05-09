<?php

declare(strict_types=1);

require dirname(__DIR__) . '/aplicacion/Arranque.php';

header('Content-Type: application/json; charset=utf-8');

echo json_encode([
    'estado' => 'ok',
    'aplicacion' => configuracion('aplicacion.nombre', 'Surcos'),
    'entorno' => configuracion('aplicacion.entorno', 'local'),
    'php' => PHP_VERSION,
    'base_datos' => BaseDatos::verificar(),
    'hora' => date(DATE_ATOM),
], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

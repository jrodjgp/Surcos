<?php

declare(strict_types=1);

require dirname(__DIR__) . '/aplicacion/Arranque.php';

header('Content-Type: application/json; charset=utf-8');

$verificacion = BaseDatos::verificar();
$mostrarDetalles = (bool) valor_entorno('MOSTRAR_DETALLE_SALUD', false);
$baseDatos = $mostrarDetalles ? $verificacion : [
    'configurada' => (bool) ($verificacion['configurada'] ?? false),
    'conectada' => (bool) ($verificacion['conectada'] ?? false),
    'driver_disponible' => (bool) ($verificacion['driver_disponible'] ?? false),
    'motor' => configuracion('base_datos.motor', 'mysql'),
];

echo json_encode([
    'estado' => 'ok',
    'aplicacion' => configuracion('aplicacion.nombre', 'Surcos'),
    'entorno' => configuracion('aplicacion.entorno', 'local'),
    'php' => PHP_VERSION,
    'requisito' => 'PHP + MySQL/MariaDB + sesiones',
    'base_datos' => $baseDatos,
    'hora' => date(DATE_ATOM),
], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

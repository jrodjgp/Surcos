<?php

declare(strict_types=1);

require dirname(__DIR__, 2) . '/aplicacion/Arranque.php';

header('Content-Type: application/json; charset=utf-8');

try {
    $pools = (new Pool())->activos();

    echo json_encode([
        'estado' => 'ok',
        'total' => count($pools),
        'pools' => array_map(static function (array $pool): array {
            return [
                'id' => $pool['id'],
                'producto' => $pool['producto'],
                'variedad' => $pool['variedad'],
                'origen' => $pool['origen'],
                'precio_grupal' => (float) $pool['precio_grupal'],
                'unidad' => $pool['unidad'],
                'personas_actuales' => (int) $pool['personas_actuales'],
                'personas_objetivo' => (int) $pool['personas_objetivo'],
                'fecha_cierre' => $pool['fecha_cierre'],
            ];
        }, $pools),
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
} catch (Throwable $excepcion) {
    http_response_code(500);
    echo json_encode([
        'estado' => 'error',
        'mensaje' => 'No se pudieron consultar los pools.',
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
}

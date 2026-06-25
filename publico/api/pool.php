<?php

declare(strict_types=1);

require dirname(__DIR__, 2) . '/aplicacion/Arranque.php';

header('Content-Type: application/json; charset=utf-8');

try {
    $id = trim((string) ($_GET['id'] ?? ''));
    $pool = $id !== '' ? (new Pool())->buscar($id) : null;

    if (!$pool) {
        http_response_code(404);
        echo json_encode([
            'estado' => 'error',
            'mensaje' => 'Pool no encontrado.',
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        exit;
    }

    echo json_encode([
        'estado' => 'ok',
        'pool' => [
            'id' => $pool['id'],
            'producto' => $pool['producto'],
            'variedad' => $pool['variedad'],
            'productor' => [
                'id' => $pool['productor_id'],
                'nombre' => $pool['productor_nombre'],
                'provincia' => $pool['productor_provincia'],
                'zona' => $pool['productor_zona'],
            ],
            'origen' => $pool['origen'],
            'estado' => $pool['estado'],
            'precio_mercado' => (float) $pool['precio_mercado'],
            'precio_grupal' => (float) $pool['precio_grupal'],
            'precio_vigente' => (float) $pool['precio_vigente'],
            'unidad' => $pool['unidad'],
            'personas_actuales' => (int) $pool['personas_actuales'],
            'personas_objetivo' => (int) $pool['personas_objetivo'],
            'cantidad_minima' => (float) $pool['cantidad_minima'],
            'fecha_cierre' => $pool['fecha_cierre'],
            'fecha_entrega' => $pool['fecha_entrega'],
            'tramos' => array_map(static fn (array $tramo): array => [
                'compradores_minimos' => (int) $tramo['compradores_minimos'],
                'precio_unitario' => (float) $tramo['precio_unitario'],
                'etiqueta' => $tramo['etiqueta'],
            ], $pool['tramos']),
            'siguiente_tramo' => $pool['siguiente_tramo'] ? [
                'compradores_minimos' => (int) $pool['siguiente_tramo']['compradores_minimos'],
                'precio_unitario' => (float) $pool['siguiente_tramo']['precio_unitario'],
                'faltan' => (int) $pool['faltan_siguiente_tramo'],
            ] : null,
        ],
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
} catch (Throwable $excepcion) {
    http_response_code(500);
    echo json_encode([
        'estado' => 'error',
        'mensaje' => 'No se pudo consultar el pool.',
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
}

<?php

declare(strict_types=1);

require dirname(__DIR__, 2) . '/aplicacion/Arranque.php';

header('Content-Type: application/json; charset=utf-8');

try {
    $productores = (new Productor())->todosConResumen();

    echo json_encode([
        'estado' => 'ok',
        'total' => count($productores),
        'productores' => array_map(static fn (array $productor): array => [
            'id' => $productor['id'],
            'nombre' => $productor['nombre'],
            'responsable' => $productor['responsable'],
            'provincia' => $productor['provincia'],
            'zona' => $productor['zona'],
            'especialidad' => $productor['especialidad'],
            'pools_total' => (int) $productor['pools_total'],
            'pools_activos' => (int) $productor['pools_activos'],
            'proximo_cierre' => $productor['proximo_cierre'],
        ], $productores),
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
} catch (Throwable $excepcion) {
    http_response_code(500);
    echo json_encode([
        'estado' => 'error',
        'mensaje' => 'No se pudieron consultar los productores.',
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
}

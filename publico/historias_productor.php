<?php

declare(strict_types=1);

require dirname(__DIR__) . '/aplicacion/Arranque.php';

(new class extends Controlador {
    public function mostrar(): void
    {
        $productorModelo = new Productor();
        $poolModelo = new Pool();
        $productor = null;
        $poolsProductor = [];
        $productores = [];
        $relacionados = [];
        $poolPrincipal = null;
        $errorDatos = null;

        try {
            $productorId = trim((string) ($_GET['productor'] ?? ''));
            $poolId = trim((string) ($_GET['pool'] ?? $_GET['grupo'] ?? $_GET['id'] ?? ''));

            if ($productorId !== '') {
                $productor = $productorModelo->buscarActivo($productorId);
            }

            if (!$productor && $poolId !== '') {
                $productor = $productorModelo->buscarActivoPorPool($poolId);
                $poolPrincipal = $poolModelo->buscar($poolId);
            }

            $productores = $productorModelo->todosConResumen();

            if (!$productor && !empty($productores)) {
                $productor = $productores[0];
            }

            if ($productor) {
                $poolsProductor = array_values(array_filter(
                    $poolModelo->poolsProductor((string) $productor['id']),
                    static fn (array $pool): bool => $pool['estado'] === 'activo' && strtotime((string) $pool['fecha_cierre']) >= time()
                ));
                $relacionados = $productorModelo->relacionados((string) $productor['id']);
                $poolPrincipal = $poolPrincipal ?: ($poolsProductor[0] ?? null);
            }
        } catch (Throwable $excepcion) {
            $errorDatos = 'No se pudieron cargar las historias. Revisa la base de datos y /salud.php.';
        }

        $this->vistaPublica('historias', [
            'tituloPagina' => 'Historias | Surcos',
            'descripcionPagina' => 'Historias de productores y pools activos en Surcos.',
            'paginaActiva' => 'historias',
            'estilosExtra' => ['marketplace.css', 'historias.css', 'pulido-landing.css'],
            'productor' => $productor,
            'productores' => $productores,
            'relacionados' => $relacionados,
            'poolsProductor' => $poolsProductor,
            'poolPrincipal' => $poolPrincipal,
            'poolModelo' => $poolModelo,
            'errorDatos' => $errorDatos,
        ]);
    }
})->mostrar();

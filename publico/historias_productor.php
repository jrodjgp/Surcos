<?php

declare(strict_types=1);

require dirname(__DIR__) . '/aplicacion/Arranque.php';

(new class extends Controlador {
    public function mostrar(): void
    {
        $this->vistaPublica('simple', [
            'tituloPagina' => 'Historias | Surcos',
            'paginaActiva' => 'historias',
            'estilosExtra' => ['marketplace.css'],
            'titulo' => 'Historias de productores',
            'texto' => 'Esta seccion se migrara despues de cerrar los requisitos PHP, MySQL y admin.',
        ]);
    }
})->mostrar();

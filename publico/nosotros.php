<?php

declare(strict_types=1);

require dirname(__DIR__) . '/aplicacion/Arranque.php';

(new class extends Controlador {
    public function mostrar(): void
    {
        $this->vistaPublica('simple', [
            'tituloPagina' => 'Nosotros | Surcos',
            'paginaActiva' => 'nosotros',
            'estilosExtra' => ['nosotros.css'],
            'titulo' => 'Surcos conecta demanda y cosecha',
            'texto' => 'Marketplace agricola panameno para pools de compra colectiva y lotes de productores.',
        ]);
    }
})->mostrar();

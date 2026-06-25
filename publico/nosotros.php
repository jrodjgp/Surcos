<?php

declare(strict_types=1);

require dirname(__DIR__) . '/aplicacion/Arranque.php';

(new class extends Controlador {
    public function mostrar(): void
    {
        $this->vistaPublica('nosotros', [
            'tituloPagina' => 'Nosotros | Surcos',
            'descripcionPagina' => 'Surcos reduce intermediarios entre productores panamenos y compradores de volumen mediante pools de compra.',
            'paginaActiva' => 'nosotros',
            'estilosExtra' => ['nosotros.css'],
        ]);
    }
})->mostrar();

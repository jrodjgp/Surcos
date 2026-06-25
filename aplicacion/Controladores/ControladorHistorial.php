<?php

declare(strict_types=1);

final class ControladorHistorial extends Controlador
{
    public function pools(): void
    {
        $usuarioId = Autenticacion::requiereUsuario();

        $this->vistaPublica('historial_pools', [
            'tituloPagina' => 'Historial de Pools | Surcos',
            'descripcionPagina' => 'Actividad y compromisos confirmados del comprador en Surcos.',
            'paginaActiva' => 'historial',
            'estilosExtra' => ['dashboard.css', 'historias.css', 'pulido-landing.css'],
            'historial' => (new Compromiso())->historialUsuario($usuarioId),
            'actividad' => (new Actividad())->listarUsuario($usuarioId),
        ]);
    }
}

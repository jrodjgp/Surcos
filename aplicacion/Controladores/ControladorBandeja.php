<?php

declare(strict_types=1);

final class ControladorBandeja extends Controlador
{
    public function mostrar(): void
    {
        $usuarioId = Autenticacion::requiereUsuarioActivo();
        $usuarioModelo = new Usuario();
        $compromisoModelo = new Compromiso();

        $this->vistaPublica('bandeja', [
            'tituloPagina' => 'Bandeja de Pools | Surcos',
            'descripcionPagina' => 'Borradores de compromisos de compra colectiva.',
            'paginaActiva' => 'bandeja',
            'estilosExtra' => ['dashboard.css', 'pulido-landing.css'],
            'borradores' => $compromisoModelo->borradores($usuarioId),
            'total' => $compromisoModelo->totalBorradores($usuarioId),
            'metodosPago' => $usuarioModelo->metodosPago($usuarioId),
        ]);
    }

    public function quitar(): void
    {
        $this->requierePostValido();
        $usuarioId = Autenticacion::requiereUsuarioActivo();
        (new Compromiso())->quitar((string) ($_POST['compromiso_id'] ?? ''), $usuarioId);
        Sesion::mensajeTemporal('exito', 'Borrador retirado de la bandeja.');
        redirigir('/bandeja.php');
    }

    public function confirmar(): void
    {
        $this->requierePostValido();
        $usuarioId = Autenticacion::requiereUsuarioActivo();
        $metodoPagoId = (string) ($_POST['metodo_pago_id'] ?? '');

        if ($metodoPagoId === '') {
            Sesion::mensajeTemporal('error', 'Selecciona un metodo de pago simulado.');
            redirigir('/bandeja.php');
        }

        try {
            $resultados = (new Compromiso())->confirmarBorradores($usuarioId, $metodoPagoId);
            $total = count($resultados);
            Sesion::mensajeTemporal('exito', "Bandeja confirmada. {$total} compromiso(s) autorizados de forma simulada.");
        } catch (Throwable $excepcion) {
            Sesion::mensajeTemporal('error', 'No se pudo confirmar la bandeja: ' . $excepcion->getMessage());
        }

        redirigir('/bandeja.php');
    }
}

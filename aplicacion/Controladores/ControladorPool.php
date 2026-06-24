<?php

declare(strict_types=1);

final class ControladorPool extends Controlador
{
    public function detalle(): void
    {
        $id = trim((string) ($_GET['id'] ?? ''));
        $pool = $id !== '' ? (new Pool())->buscar($id) : null;

        if (!$pool) {
            http_response_code(404);
            $this->vistaPublica('simple', [
                'tituloPagina' => 'Pool no encontrado | Surcos',
                'paginaActiva' => 'marketplace',
                'estilosExtra' => ['marketplace.css'],
                'titulo' => 'Pool no encontrado',
                'texto' => 'El pool solicitado no existe o ya no esta disponible.',
            ]);
            return;
        }

        $this->vistaPublica('pool', [
            'tituloPagina' => $pool['producto'] . ' | Surcos',
            'descripcionPagina' => 'Detalle de pool agricola en Surcos.',
            'paginaActiva' => 'marketplace',
            'estilosExtra' => ['marketplace.css', 'dashboard.css', 'pulido-landing.css'],
            'pool' => $pool,
            'avance' => (new Pool())->avance($pool),
        ]);
    }

    public function agregar(): void
    {
        $this->requierePostValido();

        $usuarioId = Autenticacion::requiereUsuario();
        $pool = (new Pool())->buscar((string) ($_POST['pool_id'] ?? ''));
        $cantidad = max(1, (float) ($_POST['cantidad'] ?? 1));

        if (!$pool || $pool['estado'] !== 'activo') {
            Sesion::mensajeTemporal('error', 'El pool ya no esta disponible.');
            redirigir('/');
        }

        try {
            (new Compromiso())->agregarBorrador($usuarioId, $pool, $cantidad);
            Sesion::mensajeTemporal('exito', 'Pool agregado a tu bandeja.');
            redirigir('/bandeja.php');
        } catch (Throwable $excepcion) {
            Sesion::mensajeTemporal('error', 'No se pudo agregar el pool a la bandeja.');
            redirigir('/pool.php?id=' . urlencode((string) $pool['id']));
        }
    }
}

<?php

declare(strict_types=1);

final class ControladorMarketplace extends Controlador
{
    public function inicio(): void
    {
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            $this->publicarCosecha();
            return;
        }

        $poolModelo = new Pool();
        $poolsActivos = [];
        $resumen = ['pools' => 0, 'cosechas' => 0, 'provincias' => 0, 'proximo_cierre' => null];
        $errorDatos = null;

        try {
            $poolsActivos = $poolModelo->activos();
            $resumen = $poolModelo->resumenMercado();
        } catch (Throwable $excepcion) {
            $errorDatos = 'Base de datos no disponible. Revisa /salud.php y la importacion MySQL.';
        }

        $this->vistaPublica('inicio', [
            'tituloPagina' => 'Surcos | Mercado de Pools',
            'descripcionPagina' => 'Surcos conecta compradores en pools de compra colectiva con productores que publican lotes de cosecha.',
            'paginaActiva' => 'marketplace',
            'estilosExtra' => ['marketplace.css', 'mapa.css', 'pulido-landing.css'],
            'poolsActivos' => $poolsActivos,
            'resumen' => $resumen,
            'errorDatos' => $errorDatos,
            'poolModelo' => $poolModelo,
        ]);
    }

    private function publicarCosecha(): void
    {
        $this->requierePostValido();

        $producto = trim((string) ($_POST['producto'] ?? ''));
        $variedad = trim((string) ($_POST['variedad'] ?? ''));
        $cantidad = (float) ($_POST['cantidadKg'] ?? 0);
        $precio = (float) ($_POST['precioMinimo'] ?? 0);
        $ubicacion = trim((string) ($_POST['ubicacion'] ?? ''));

        if (mb_strlen($producto) < 3 || mb_strlen($variedad) < 3 || $cantidad < 25 || $precio < 0.25 || $ubicacion === '') {
            Sesion::mensajeTemporal('error', 'Revisa los datos del lote antes de publicar.');
            redirigir('/#registro-cosecha');
        }

        try {
            (new Pool())->crearDesdeFormulario($_POST);
            Sesion::mensajeTemporal('exito', 'Cosecha publicada como pool activo de demostracion.');
        } catch (Throwable $excepcion) {
            Sesion::mensajeTemporal('error', 'No se pudo publicar la cosecha. Verifica la base de datos.');
        }

        redirigir('/#pools-activos');
    }
}

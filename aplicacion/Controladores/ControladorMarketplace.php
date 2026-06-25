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
        $nodosRetiro = [];
        $usuarioActual = null;
        $productorActual = null;
        $errorDatos = null;

        try {
            $poolsActivos = $poolModelo->activos();
            $resumen = $poolModelo->resumenMercado();
            $nodosRetiro = $poolModelo->nodosActivos();

            $usuarioId = Autenticacion::usuarioId();
            if ($usuarioId !== null) {
                $usuarioActual = (new Usuario())->buscar($usuarioId);
                if (($usuarioActual['rol'] ?? '') === 'productor') {
                    $productorActual = (new Productor())->buscarPorUsuario($usuarioId);
                }
            }
        } catch (Throwable $excepcion) {
            $errorDatos = 'Base de datos no disponible. Revisa /salud.php y la importacion MySQL.';
        }

        $this->vistaPublica('inicio', [
            'tituloPagina' => 'Surcos | Mercado de Pools',
            'descripcionPagina' => 'Surcos conecta compradores en pools de compra colectiva con productores que publican lotes de cosecha.',
            'paginaActiva' => 'marketplace',
            'estilosExtra' => ['marketplace.css', 'mapa.css', 'historias.css', 'pulido-landing.css'],
            'poolsActivos' => $poolsActivos,
            'resumen' => $resumen,
            'nodosRetiro' => $nodosRetiro,
            'usuarioActual' => $usuarioActual,
            'productorActual' => $productorActual,
            'errorDatos' => $errorDatos,
            'poolModelo' => $poolModelo,
        ]);
    }

    private function publicarCosecha(): void
    {
        $this->requierePostValido();
        $usuarioId = Autenticacion::requiereUsuario();
        $usuario = (new Usuario())->buscar($usuarioId);
        $productor = (new Productor())->buscarPorUsuario($usuarioId);

        $producto = trim((string) ($_POST['producto'] ?? ''));
        $variedad = trim((string) ($_POST['variedad'] ?? ''));
        $cantidad = (float) ($_POST['cantidadKg'] ?? 0);
        $precio = (float) ($_POST['precioMinimo'] ?? 0);
        $ubicacion = trim((string) ($_POST['ubicacion'] ?? ''));
        $categoria = (string) ($_POST['categoria'] ?? '');
        $unidad = (string) ($_POST['unidad'] ?? '');
        $nodoRetiro = (string) ($_POST['nodoRetiro'] ?? '');
        $fechaCierre = (string) ($_POST['fechaCierre'] ?? '');
        $fechaEntrega = (string) ($_POST['fechaEntrega'] ?? '');
        $personasObjetivo = (int) ($_POST['personasObjetivo'] ?? 0);
        $modelosEntrega = ['Retiro en Nodo', 'Envio a Domicilio', 'Lote Empresarial'];
        $categorias = ['cafe', 'hortalizas', 'miel', 'cacao', 'aceite', 'frutas', 'granos', 'ganaderia'];
        $unidades = ['kg', 'lb', 'caja', 'botella'];
        $nodosValidos = array_column((new Pool())->nodosActivos(), 'id');

        if (!$usuario || $usuario['estado'] !== 'activo' || $usuario['rol'] !== 'productor' || !$productor || $productor['estado'] !== 'activo') {
            Sesion::mensajeTemporal('error', 'Solo productores activos y verificados pueden publicar cosechas.');
            redirigir('/#registro-cosecha');
        }

        if (
            mb_strlen($producto) < 3
            || mb_strlen($variedad) < 3
            || $cantidad < 25
            || $precio < 0.25
            || $ubicacion === ''
            || !in_array($categoria, $categorias, true)
            || !in_array($unidad, $unidades, true)
            || !in_array($nodoRetiro, $nodosValidos, true)
            || $personasObjetivo < 5
            || !$this->fechaFutura($fechaCierre)
            || !$this->fechaFutura($fechaEntrega)
            || strtotime($fechaEntrega) <= strtotime($fechaCierre)
            || !in_array((string) ($_POST['modeloEntrega'] ?? ''), $modelosEntrega, true)
        ) {
            Sesion::mensajeTemporal('error', 'Revisa los datos del lote antes de publicar.');
            redirigir('/#registro-cosecha');
        }

        try {
            (new Pool())->crearDesdeFormulario($_POST, $productor);
            Sesion::mensajeTemporal('exito', 'Cosecha publicada como pool activo.');
        } catch (Throwable $excepcion) {
            Sesion::mensajeTemporal('error', 'No se pudo publicar la cosecha. Verifica la base de datos.');
        }

        redirigir('/#pools-activos');
    }

    private function fechaFutura(string $fecha): bool
    {
        $tiempo = strtotime($fecha);
        return $tiempo !== false && $tiempo > strtotime('today');
    }
}

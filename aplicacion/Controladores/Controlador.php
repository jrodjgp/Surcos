<?php

declare(strict_types=1);

abstract class Controlador
{
    protected function vistaPublica(string $vista, array $datos = []): void
    {
        $datos += [
            'tituloPagina' => 'Surcos',
            'descripcionPagina' => 'Surcos conecta compradores y productores por pools de compra agricola.',
            'paginaActiva' => '',
            'estilosExtra' => [],
            'cargarBootstrap' => false,
            'cargarBootstrapJs' => false,
        ];

        renderizar_vista('parciales/cabecera.php', $datos);
        renderizar_vista('parciales/navegacion.php', $datos);
        renderizar_vista('parciales/mensajes.php');
        renderizar_vista('paginas/' . $vista . '.php', $datos);
        renderizar_vista('parciales/pie.php', $datos);
    }

    protected function vistaAdmin(string $vista, array $datos = []): void
    {
        $datos += [
            'tituloPagina' => 'Admin Surcos',
            'descripcionPagina' => 'Panel administrativo de Surcos.',
            'estilosExtra' => ['admin.css'],
            'cargarBootstrap' => false,
            'cargarBootstrapJs' => false,
        ];

        renderizar_vista('parciales/cabecera.php', $datos);
        echo '<body class="admin-body">';
        renderizar_vista('parciales/mensajes.php');
        renderizar_vista('admin/' . $vista . '.php', $datos);
        renderizar_vista('parciales/pie.php', $datos);
    }

    protected function requierePostValido(): void
    {
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            redirigir('/');
        }

        if (!ProteccionCsrf::validarSolicitud()) {
            Sesion::mensajeTemporal('error', 'La sesion del formulario expiro. Intenta nuevamente.');
            redirigir($_SERVER['HTTP_REFERER'] ?? '/');
        }
    }
}

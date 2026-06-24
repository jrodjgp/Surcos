<?php

declare(strict_types=1);

final class ControladorContacto extends Controlador
{
    public function manejar(): void
    {
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            $this->guardar();
            return;
        }

        $this->vistaPublica('contacto', [
            'tituloPagina' => 'Surcos | Solicitar Afiliacion',
            'descripcionPagina' => 'Formulario de afiliacion para compradores, productores, empresas y aliados logisticos.',
            'paginaActiva' => 'contacto',
            'estilosExtra' => ['contacto.css'],
        ]);
    }

    private function guardar(): void
    {
        $this->requierePostValido();

        $datos = [
            'nombre' => trim((string) ($_POST['nombre'] ?? '')),
            'correo' => trim((string) ($_POST['correo'] ?? '')),
            'telefono' => trim((string) ($_POST['telefono'] ?? '')),
            'tipo_usuario' => $_POST['tipo_usuario'] ?? '',
            'asunto' => trim((string) ($_POST['asunto'] ?? '')),
            'mensaje' => trim((string) ($_POST['mensaje'] ?? '')),
            'acepta_contacto' => $_POST['acepta_contacto'] ?? null,
        ];

        $tipos = ['comprador', 'productor', 'empresa', 'aliado_logistico'];

        if (
            mb_strlen($datos['nombre']) < 3 ||
            !filter_var($datos['correo'], FILTER_VALIDATE_EMAIL) ||
            !in_array($datos['tipo_usuario'], $tipos, true) ||
            mb_strlen($datos['asunto']) < 4 ||
            mb_strlen($datos['mensaje']) < 10 ||
            empty($datos['acepta_contacto'])
        ) {
            Sesion::mensajeTemporal('error', 'Completa los campos requeridos y acepta el contacto.');
            redirigir('/contacto.php');
        }

        try {
            (new SolicitudContacto())->crear($datos);
            Sesion::mensajeTemporal('exito', 'Solicitud recibida. El equipo de Surcos la vera en el panel admin.');
        } catch (Throwable $excepcion) {
            Sesion::mensajeTemporal('error', 'No se pudo guardar la solicitud. Revisa la base de datos.');
        }

        redirigir('/contacto.php');
    }
}

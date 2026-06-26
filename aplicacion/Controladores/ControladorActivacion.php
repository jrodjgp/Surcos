<?php

declare(strict_types=1);

final class ControladorActivacion extends Controlador
{
    public function manejar(): void
    {
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            $this->activar();
            return;
        }

        $usuarioId = Autenticacion::requiereUsuario();
        $usuario = (new Usuario())->buscar($usuarioId);

        if (!$usuario) {
            Autenticacion::cerrar();
            Sesion::mensajeTemporal('error', 'La sesion ya no es valida. Ingresa nuevamente.');
            redirigir('/ingreso.php');
        }

        if (($usuario['estado'] ?? '') === 'activo' && (int) ($usuario['debe_cambiar_clave'] ?? 0) === 0) {
            redirigir(($usuario['rol'] ?? '') === 'productor' ? '/productor/' : '/bandeja.php');
        }

        $this->vistaPublica('activar_cuenta', [
            'tituloPagina' => 'Activar cuenta | Surcos',
            'descripcionPagina' => 'Cambio de clave temporal para activar cuenta Surcos.',
            'paginaActiva' => 'ingreso',
            'estilosExtra' => ['autenticacion.css', 'pulido-landing.css'],
            'usuario' => $usuario,
        ]);
    }

    private function activar(): void
    {
        $this->requierePostValido();

        $usuarioId = Autenticacion::requiereUsuario();
        $usuarioModelo = new Usuario();
        $usuario = $usuarioModelo->buscar($usuarioId);

        if (!$usuario) {
            Autenticacion::cerrar();
            Sesion::mensajeTemporal('error', 'La sesion ya no es valida. Ingresa nuevamente.');
            redirigir('/ingreso.php');
        }

        if (($usuario['estado'] ?? '') === 'activo' && (int) ($usuario['debe_cambiar_clave'] ?? 0) === 0) {
            redirigir(($usuario['rol'] ?? '') === 'productor' ? '/productor/' : '/bandeja.php');
        }

        $claveActual = (string) ($_POST['clave_actual'] ?? '');
        $claveNueva = (string) ($_POST['clave_nueva'] ?? '');
        $claveConfirmacion = (string) ($_POST['clave_confirmacion'] ?? '');

        if (!password_verify($claveActual, (string) $usuario['clave_hash'])) {
            Sesion::mensajeTemporal('error', 'La clave temporal no coincide.');
            redirigir('/activar_cuenta.php');
        }

        if (strlen($claveNueva) < 8 || $claveNueva !== $claveConfirmacion) {
            Sesion::mensajeTemporal('error', 'La nueva clave debe tener 8 caracteres o mas y coincidir con la confirmacion.');
            redirigir('/activar_cuenta.php');
        }

        try {
            $usuarioModelo->activarConNuevaClave($usuarioId, $claveNueva);

            if (($usuario['rol'] ?? '') === 'productor') {
                (new Productor())->activarPorUsuario($usuarioId);
            }

            $usuarioActualizado = $usuarioModelo->buscar($usuarioId);
            if ($usuarioActualizado) {
                Autenticacion::iniciarUsuario($usuarioActualizado);
            }

            Sesion::mensajeTemporal('exito', 'Cuenta activada correctamente.');
            redirigir(($usuario['rol'] ?? '') === 'productor' ? '/productor/' : '/bandeja.php');
        } catch (Throwable $excepcion) {
            Sesion::mensajeTemporal('error', 'No se pudo activar la cuenta. Intentalo nuevamente.');
            redirigir('/activar_cuenta.php');
        }
    }
}

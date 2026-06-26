<?php

declare(strict_types=1);

final class ControladorIngreso extends Controlador
{
    public function manejar(): void
    {
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            $this->ingresar();
            return;
        }

        $this->vistaPublica('ingreso', [
            'tituloPagina' => 'Surcos | Ingreso',
            'descripcionPagina' => 'Ingreso de compradores y productores a Surcos.',
            'paginaActiva' => 'ingreso',
            'estilosExtra' => ['autenticacion.css'],
        ]);
    }

    public function salir(): void
    {
        Autenticacion::cerrar();
        Sesion::mensajeTemporal('exito', 'Sesion cerrada correctamente.');
        redirigir('/');
    }

    private function ingresar(): void
    {
        $this->requierePostValido();

        $correo = trim((string) ($_POST['correo'] ?? ''));
        $clave = (string) ($_POST['clave'] ?? '');

        try {
            $usuario = (new Usuario())->buscarPorCorreo($correo);

            if (!$usuario || !password_verify($clave, (string) $usuario['clave_hash'])) {
                Sesion::mensajeTemporal('error', 'Credenciales invalidas.');
                redirigir('/ingreso.php');
            }

            if ($usuario['estado'] === 'suspendido') {
                Sesion::mensajeTemporal('error', 'La cuenta esta suspendida.');
                redirigir('/ingreso.php');
            }

            Autenticacion::iniciarUsuario($usuario);

            if ($usuario['estado'] !== 'activo' || (int) ($usuario['debe_cambiar_clave'] ?? 0) === 1) {
                Sesion::mensajeTemporal('exito', 'Clave temporal validada. Crea una clave nueva para activar tu cuenta.');
                redirigir('/activar_cuenta.php');
            }

            Sesion::mensajeTemporal('exito', 'Bienvenido a tu Bandeja de Pools.');
            redirigir('/bandeja.php');
        } catch (Throwable $excepcion) {
            Sesion::mensajeTemporal('error', 'No se pudo iniciar sesion. Revisa la base de datos.');
            redirigir('/ingreso.php');
        }
    }
}

<?php

declare(strict_types=1);

final class ControladorAdmin extends Controlador
{
    public function login(): void
    {
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            $this->ingresar();
            return;
        }

        if (Autenticacion::adminId() !== null) {
            redirigir('/admin/solicitudes.php');
        }

        $this->vistaAdmin('login', ['tituloPagina' => 'Admin Surcos | Ingreso']);
    }

    public function salir(): void
    {
        Autenticacion::cerrar();
        Sesion::mensajeTemporal('exito', 'Sesion admin cerrada.');
        redirigir('/admin/');
    }

    public function solicitudes(): void
    {
        Autenticacion::requiereAdmin();
        $estado = trim((string) ($_GET['estado'] ?? ''));
        $modelo = new SolicitudContacto();

        $this->vistaAdmin('solicitudes', [
            'tituloPagina' => 'Solicitudes | Admin Surcos',
            'solicitudes' => $modelo->listar($estado !== '' ? $estado : null),
            'conteos' => $modelo->contarPorEstado(),
            'estadoActual' => $estado,
        ]);
    }

    public function solicitud(): void
    {
        $adminId = Autenticacion::requiereAdmin();
        $modelo = new SolicitudContacto();
        $id = (string) ($_GET['id'] ?? $_POST['id'] ?? '');

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            $this->requierePostValido();
            $this->actualizarSolicitud($modelo, $adminId, $id);
            return;
        }

        $solicitud = $modelo->buscar($id);

        if (!$solicitud) {
            Sesion::mensajeTemporal('error', 'Solicitud no encontrada.');
            redirigir('/admin/solicitudes.php');
        }

        $this->vistaAdmin('solicitud', [
            'tituloPagina' => 'Detalle de solicitud | Admin Surcos',
            'solicitud' => $solicitud,
            'eventos' => $modelo->eventos($id),
        ]);
    }

    public function pools(): void
    {
        Autenticacion::requiereAdmin();

        $this->vistaAdmin('pools', [
            'tituloPagina' => 'Pools | Admin Surcos',
            'pools' => (new Pool())->todosAdmin(),
        ]);
    }

    private function ingresar(): void
    {
        $this->requierePostValido();

        $correo = trim((string) ($_POST['correo'] ?? ''));
        $clave = (string) ($_POST['clave'] ?? '');
        $admin = (new Administrador())->buscarPorCorreo($correo);

        if (!$admin || !password_verify($clave, (string) $admin['clave_hash'])) {
            Sesion::mensajeTemporal('error', 'Credenciales admin invalidas.');
            redirigir('/admin/');
        }

        Autenticacion::iniciarAdmin($admin);
        redirigir('/admin/solicitudes.php');
    }

    private function actualizarSolicitud(SolicitudContacto $modelo, string $adminId, string $id): void
    {
        $accion = (string) ($_POST['accion'] ?? '');
        $nota = trim((string) ($_POST['nota'] ?? ''));
        $solicitud = $modelo->buscar($id);

        if (!$solicitud) {
            Sesion::mensajeTemporal('error', 'Solicitud no encontrada.');
            redirigir('/admin/solicitudes.php');
        }

        if ($accion === 'aprobar') {
            $claveTemporal = 'Surcos-' . random_int(1000, 9999);

            try {
                if (empty($solicitud['usuario_creado_id'])) {
                    $usuarioId = (new Usuario())->crearPendienteDesdeSolicitud($solicitud, $claveTemporal);
                    $modelo->vincularUsuario($id, $usuarioId);
                    $nota = trim($nota . "\nClave temporal mostrada una vez: {$claveTemporal}");
                }

                $modelo->cambiarEstado($id, 'aprobada', $adminId, $nota ?: 'Solicitud aprobada.');
                Sesion::mensajeTemporal('exito', 'Solicitud aprobada. Clave temporal: ' . $claveTemporal);
            } catch (Throwable $excepcion) {
                Sesion::mensajeTemporal('error', 'No se pudo aprobar la solicitud. Puede existir un usuario con ese correo.');
            }
        } elseif ($accion === 'rechazar') {
            $modelo->cambiarEstado($id, 'rechazada', $adminId, $nota ?: 'Solicitud rechazada.');
            Sesion::mensajeTemporal('exito', 'Solicitud rechazada.');
        } else {
            $modelo->cambiarEstado($id, 'en_revision', $adminId, $nota ?: 'Nota administrativa registrada.');
            Sesion::mensajeTemporal('exito', 'Nota registrada.');
        }

        redirigir('/admin/solicitud.php?id=' . urlencode($id));
    }
}

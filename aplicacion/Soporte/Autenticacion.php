<?php

declare(strict_types=1);

final class Autenticacion
{
    public static function iniciarUsuario(array $usuario): void
    {
        Sesion::regenerar();
        Sesion::guardar('usuario_id', $usuario['id']);
        Sesion::guardar('usuario_nombre', $usuario['nombre']);
        Sesion::guardar('usuario_rol', $usuario['rol']);
        Sesion::guardar('usuario_estado', $usuario['estado'] ?? 'pendiente');
        Sesion::guardar('usuario_debe_cambiar_clave', (int) ($usuario['debe_cambiar_clave'] ?? 0));
        Sesion::olvidar('admin_id');
    }

    public static function iniciarAdmin(array $admin): void
    {
        Sesion::regenerar();
        Sesion::guardar('admin_id', $admin['id']);
        Sesion::guardar('admin_nombre', $admin['nombre']);
        Sesion::olvidar('usuario_id');
        Sesion::olvidar('usuario_estado');
        Sesion::olvidar('usuario_debe_cambiar_clave');
    }

    public static function cerrar(): void
    {
        Sesion::olvidar('usuario_id');
        Sesion::olvidar('usuario_nombre');
        Sesion::olvidar('usuario_rol');
        Sesion::olvidar('usuario_estado');
        Sesion::olvidar('usuario_debe_cambiar_clave');
        Sesion::olvidar('admin_id');
        Sesion::olvidar('admin_nombre');
        Sesion::regenerar();
    }

    public static function usuarioId(): ?string
    {
        $id = Sesion::obtener('usuario_id');
        return is_string($id) && $id !== '' ? $id : null;
    }

    public static function usuarioRol(): ?string
    {
        $rol = Sesion::obtener('usuario_rol');
        return is_string($rol) && $rol !== '' ? $rol : null;
    }

    public static function adminId(): ?string
    {
        $id = Sesion::obtener('admin_id');
        return is_string($id) && $id !== '' ? $id : null;
    }

    public static function requiereUsuario(): string
    {
        $id = self::usuarioId();

        if ($id === null) {
            Sesion::mensajeTemporal('error', 'Inicia sesion para continuar con tu bandeja de pools.');
            redirigir('/ingreso.php');
        }

        return $id;
    }

    public static function requiereUsuarioActivo(): string
    {
        $id = self::requiereUsuario();
        $estado = Sesion::obtener('usuario_estado', '');
        $debeCambiarClave = (int) Sesion::obtener('usuario_debe_cambiar_clave', 1);

        if ($estado !== 'activo' || $debeCambiarClave === 1) {
            Sesion::mensajeTemporal('error', 'Activa tu cuenta antes de continuar.');
            redirigir('/activar_cuenta.php');
        }

        return $id;
    }

    public static function requiereAdmin(): string
    {
        $id = self::adminId();

        if ($id === null) {
            Sesion::mensajeTemporal('error', 'Ingresa como administrador para continuar.');
            redirigir('/admin/');
        }

        return $id;
    }
}

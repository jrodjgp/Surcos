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
        Sesion::olvidar('admin_id');
    }

    public static function iniciarAdmin(array $admin): void
    {
        Sesion::regenerar();
        Sesion::guardar('admin_id', $admin['id']);
        Sesion::guardar('admin_nombre', $admin['nombre']);
        Sesion::olvidar('usuario_id');
    }

    public static function cerrar(): void
    {
        Sesion::olvidar('usuario_id');
        Sesion::olvidar('usuario_nombre');
        Sesion::olvidar('usuario_rol');
        Sesion::olvidar('admin_id');
        Sesion::olvidar('admin_nombre');
        Sesion::regenerar();
    }

    public static function usuarioId(): ?string
    {
        $id = Sesion::obtener('usuario_id');
        return is_string($id) && $id !== '' ? $id : null;
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

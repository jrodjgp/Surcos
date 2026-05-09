<?php

declare(strict_types=1);

final class Sesion
{
    public static function iniciar(array $configuracion): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            return;
        }

        $sesion = $configuracion['sesion'] ?? [];
        $segura = (bool) ($sesion['segura'] ?? false);
        $ruta = (string) ($sesion['ruta'] ?? '');

        if ($ruta !== '') {
            if (!is_dir($ruta)) {
                mkdir($ruta, 0775, true);
            }

            session_save_path($ruta);
        }

        session_name((string) ($sesion['nombre'] ?? 'surcos_session'));
        session_set_cookie_params([
            'lifetime' => (int) ($sesion['duracion'] ?? 7200),
            'path' => '/',
            'domain' => '',
            'secure' => $segura,
            'httponly' => true,
            'samesite' => 'Lax',
        ]);

        session_start();
    }

    public static function obtener(string $clave, mixed $defecto = null): mixed
    {
        return $_SESSION[$clave] ?? $defecto;
    }

    public static function guardar(string $clave, mixed $valor): void
    {
        $_SESSION[$clave] = $valor;
    }

    public static function olvidar(string $clave): void
    {
        unset($_SESSION[$clave]);
    }

    public static function mensajeTemporal(string $clave, string $mensaje): void
    {
        $_SESSION['_mensajes'][$clave] = $mensaje;
    }

    public static function consumirMensaje(string $clave): ?string
    {
        $mensaje = $_SESSION['_mensajes'][$clave] ?? null;
        unset($_SESSION['_mensajes'][$clave]);

        return $mensaje;
    }

    public static function regenerar(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_regenerate_id(true);
        }
    }
}

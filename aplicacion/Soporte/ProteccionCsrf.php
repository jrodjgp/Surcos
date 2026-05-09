<?php

declare(strict_types=1);

final class ProteccionCsrf
{
    private const CLAVE_SESION = '_token_csrf';

    public static function token(): string
    {
        $token = Sesion::obtener(self::CLAVE_SESION);

        if (!is_string($token) || $token === '') {
            $token = bin2hex(random_bytes(32));
            Sesion::guardar(self::CLAVE_SESION, $token);
        }

        return $token;
    }

    public static function validar(?string $token): bool
    {
        $actual = Sesion::obtener(self::CLAVE_SESION);

        return is_string($token)
            && is_string($actual)
            && hash_equals($actual, $token);
    }

    public static function validarSolicitud(): bool
    {
        return self::validar($_POST['_token'] ?? null);
    }
}

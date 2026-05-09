<?php

declare(strict_types=1);

final class BaseDatos
{
    private static ?PDO $conexion = null;

    public static function configurada(): bool
    {
        return trim((string) configuracion('base_datos.url', '')) !== '';
    }

    public static function conexion(): PDO
    {
        if (self::$conexion instanceof PDO) {
            return self::$conexion;
        }

        if (!extension_loaded('pdo_pgsql')) {
            throw new RuntimeException('La extension pdo_pgsql no esta habilitada en PHP.');
        }

        $url = trim((string) configuracion('base_datos.url', ''));

        if ($url === '') {
            throw new RuntimeException('La variable URL_BASE_DATOS no esta configurada.');
        }

        $opciones = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        self::$conexion = new PDO(self::crearDsn($url), self::obtenerUsuario($url), self::obtenerClave($url), $opciones);
        self::$conexion->exec("SET TIME ZONE 'America/Bogota'");

        return self::$conexion;
    }

    public static function verificar(): array
    {
        if (!self::configurada()) {
            return [
                'configurada' => false,
                'conectada' => false,
                'driver_disponible' => extension_loaded('pdo_pgsql'),
                'mensaje' => 'URL_BASE_DATOS pendiente.',
            ];
        }

        try {
            $resultado = self::conexion()->query('select current_database() as base, current_user as usuario')->fetch();

            return [
                'configurada' => true,
                'conectada' => true,
                'driver_disponible' => extension_loaded('pdo_pgsql'),
                'base' => $resultado['base'] ?? null,
                'usuario' => $resultado['usuario'] ?? null,
            ];
        } catch (Throwable $excepcion) {
            return [
                'configurada' => true,
                'conectada' => false,
                'driver_disponible' => extension_loaded('pdo_pgsql'),
                'mensaje' => $excepcion->getMessage(),
            ];
        }
    }

    private static function crearDsn(string $url): string
    {
        if (str_starts_with($url, 'pgsql:')) {
            return $url;
        }

        $partes = parse_url($url);

        if (!is_array($partes) || empty($partes['host'])) {
            throw new InvalidArgumentException('URL_BASE_DATOS debe ser una URI PostgreSQL valida.');
        }

        $host = $partes['host'];
        $puerto = $partes['port'] ?? 5432;
        $base = ltrim((string) ($partes['path'] ?? '/postgres'), '/');
        $consulta = [];

        if (!empty($partes['query'])) {
            parse_str($partes['query'], $consulta);
        }

        $ssl = (($consulta['sslmode'] ?? '') === 'disable') ? '' : ';sslmode=require';

        return "pgsql:host={$host};port={$puerto};dbname={$base}{$ssl}";
    }

    private static function obtenerUsuario(string $url): ?string
    {
        if (str_starts_with($url, 'pgsql:')) {
            return valor_entorno('USUARIO_BASE_DATOS');
        }

        $partes = parse_url($url);
        return isset($partes['user']) ? urldecode((string) $partes['user']) : null;
    }

    private static function obtenerClave(string $url): ?string
    {
        if (str_starts_with($url, 'pgsql:')) {
            return valor_entorno('CLAVE_BASE_DATOS');
        }

        $partes = parse_url($url);
        return isset($partes['pass']) ? urldecode((string) $partes['pass']) : null;
    }
}

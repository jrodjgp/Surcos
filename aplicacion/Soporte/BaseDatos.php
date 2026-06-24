<?php

declare(strict_types=1);

final class BaseDatos
{
    private static ?PDO $conexion = null;

    public static function configurada(): bool
    {
        return trim((string) configuracion('base_datos.base', '')) !== '';
    }

    public static function conexion(): PDO
    {
        if (self::$conexion instanceof PDO) {
            return self::$conexion;
        }

        if (!extension_loaded('pdo_mysql')) {
            throw new RuntimeException('La extension pdo_mysql no esta habilitada en PHP.');
        }

        $opciones = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        self::$conexion = new PDO(self::crearDsn(), self::obtenerUsuario(), self::obtenerClave(), $opciones);
        self::$conexion->exec("SET time_zone = '-05:00'");

        return self::$conexion;
    }

    public static function verificar(): array
    {
        if (!self::configurada()) {
            return [
                'configurada' => false,
                'conectada' => false,
                'driver_disponible' => extension_loaded('pdo_mysql'),
                'mensaje' => 'MYSQL_DATABASE pendiente.',
            ];
        }

        try {
            $resultado = self::conexion()->query('select database() as base, current_user() as usuario')->fetch();

            return [
                'configurada' => true,
                'conectada' => true,
                'driver_disponible' => extension_loaded('pdo_mysql'),
                'motor' => configuracion('base_datos.motor', 'mysql'),
                'base' => $resultado['base'] ?? null,
                'usuario' => $resultado['usuario'] ?? null,
            ];
        } catch (Throwable $excepcion) {
            return [
                'configurada' => true,
                'conectada' => false,
                'driver_disponible' => extension_loaded('pdo_mysql'),
                'motor' => configuracion('base_datos.motor', 'mysql'),
                'mensaje' => $excepcion->getMessage(),
            ];
        }
    }

    private static function crearDsn(): string
    {
        $url = trim((string) configuracion('base_datos.url', ''));

        if ($url !== '') {
            if (str_starts_with($url, 'mysql:') && !str_starts_with($url, 'mysql://')) {
                return $url;
            }

            $partes = parse_url($url);

            if (!is_array($partes) || empty($partes['host']) || ($partes['scheme'] ?? '') !== 'mysql') {
                throw new InvalidArgumentException('URL_MYSQL debe ser una URI MySQL valida.');
            }

            $host = $partes['host'];
            $puerto = $partes['port'] ?? 3306;
            $base = ltrim((string) ($partes['path'] ?? '/surcos'), '/');
            $charset = configuracion('base_datos.charset', 'utf8mb4');

            return "mysql:host={$host};port={$puerto};dbname={$base};charset={$charset}";
        }

        $host = configuracion('base_datos.host', '127.0.0.1');
        $puerto = configuracion('base_datos.puerto', 3306);
        $base = configuracion('base_datos.base', 'surcos');
        $charset = configuracion('base_datos.charset', 'utf8mb4');

        return "mysql:host={$host};port={$puerto};dbname={$base};charset={$charset}";
    }

    private static function obtenerUsuario(): ?string
    {
        $url = trim((string) configuracion('base_datos.url', ''));

        if ($url !== '' && str_starts_with($url, 'mysql://')) {
            $partes = parse_url($url);
            return isset($partes['user']) ? urldecode((string) $partes['user']) : null;
        }

        return (string) configuracion('base_datos.usuario', 'root');
    }

    private static function obtenerClave(): ?string
    {
        $url = trim((string) configuracion('base_datos.url', ''));

        if ($url !== '' && str_starts_with($url, 'mysql://')) {
            $partes = parse_url($url);
            return isset($partes['pass']) ? urldecode((string) $partes['pass']) : null;
        }

        return (string) configuracion('base_datos.clave', '');
    }
}

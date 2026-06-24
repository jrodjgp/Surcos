<?php

declare(strict_types=1);

abstract class Modelo
{
    protected function bd(): PDO
    {
        return BaseDatos::conexion();
    }

    protected function ejecutar(string $sql, array $parametros = []): PDOStatement
    {
        $sentencia = $this->bd()->prepare($sql);
        $sentencia->execute($parametros);

        return $sentencia;
    }

    protected function todos(string $sql, array $parametros = []): array
    {
        return $this->ejecutar($sql, $parametros)->fetchAll();
    }

    protected function uno(string $sql, array $parametros = []): ?array
    {
        $fila = $this->ejecutar($sql, $parametros)->fetch();

        return is_array($fila) ? $fila : null;
    }

    protected function id(string $prefijo): string
    {
        return $prefijo . '-' . bin2hex(random_bytes(8));
    }
}

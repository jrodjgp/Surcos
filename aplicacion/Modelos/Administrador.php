<?php

declare(strict_types=1);

final class Administrador extends Modelo
{
    public function buscarPorCorreo(string $correo): ?array
    {
        return $this->uno(
            'select * from administradores where correo = :correo and activo = 1 limit 1',
            ['correo' => mb_strtolower(trim($correo))]
        );
    }
}

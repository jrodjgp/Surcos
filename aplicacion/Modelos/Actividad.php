<?php

declare(strict_types=1);

final class Actividad extends Modelo
{
    public function listarUsuario(string $usuarioId): array
    {
        return $this->todos(
            'select *
               from actividad
              where usuario_id = :usuario_id
           order by fecha desc, creado_en desc',
            ['usuario_id' => $usuarioId]
        );
    }
}

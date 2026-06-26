<?php

declare(strict_types=1);

final class Usuario extends Modelo
{
    public function buscarPorCorreo(string $correo): ?array
    {
        return $this->uno(
            'select * from usuarios where correo = :correo limit 1',
            ['correo' => mb_strtolower(trim($correo))]
        );
    }

    public function buscar(string $id): ?array
    {
        return $this->uno('select * from usuarios where id = :id limit 1', ['id' => $id]);
    }

    public function metodosPago(string $usuarioId): array
    {
        return $this->todos(
            'select * from metodos_pago where usuario_id = :usuario_id and activo = 1 order by principal desc, creado_en asc',
            ['usuario_id' => $usuarioId]
        );
    }

    public function activarConNuevaClave(string $id, string $claveNueva): void
    {
        $this->ejecutar(
            'update usuarios
                set clave_hash = :clave_hash,
                    estado = "activo",
                    debe_cambiar_clave = 0
              where id = :id
                and (estado = "pendiente" or debe_cambiar_clave = 1)',
            [
                'id' => $id,
                'clave_hash' => password_hash($claveNueva, PASSWORD_DEFAULT),
            ]
        );
    }

    public function crearPendienteDesdeSolicitud(array $solicitud, string $claveTemporal): string
    {
        $id = $this->id('usr');
        $nombre = trim((string) $solicitud['nombre']);
        $iniciales = $this->iniciales($nombre);

        $this->ejecutar(
            'insert into usuarios (
                id, nombre, correo, clave_hash, telefono, rol, estado, provincia,
                iniciales, debe_cambiar_clave
            ) values (
                :id, :nombre, :correo, :clave_hash, :telefono, :rol, "pendiente", null,
                :iniciales, 1
            )',
            [
                'id' => $id,
                'nombre' => $nombre,
                'correo' => mb_strtolower(trim((string) $solicitud['correo'])),
                'clave_hash' => password_hash($claveTemporal, PASSWORD_DEFAULT),
                'telefono' => $solicitud['telefono'] ?? null,
                'rol' => $solicitud['tipo_usuario'],
                'iniciales' => $iniciales,
            ]
        );

        return $id;
    }

    private function iniciales(string $nombre): string
    {
        $partes = preg_split('/\s+/', trim($nombre)) ?: [];
        $iniciales = '';

        foreach (array_slice($partes, 0, 2) as $parte) {
            $iniciales .= mb_strtoupper(mb_substr($parte, 0, 1));
        }

        return $iniciales !== '' ? $iniciales : 'SR';
    }
}

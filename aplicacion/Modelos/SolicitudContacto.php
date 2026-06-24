<?php

declare(strict_types=1);

final class SolicitudContacto extends Modelo
{
    public function crear(array $datos): string
    {
        $id = $this->id('sol');

        $this->ejecutar(
            'insert into solicitudes_contacto (
                id, nombre, correo, telefono, tipo_usuario, asunto, mensaje, acepta_contacto
            ) values (
                :id, :nombre, :correo, :telefono, :tipo_usuario, :asunto, :mensaje, :acepta_contacto
            )',
            [
                'id' => $id,
                'nombre' => trim((string) $datos['nombre']),
                'correo' => mb_strtolower(trim((string) $datos['correo'])),
                'telefono' => trim((string) ($datos['telefono'] ?? '')),
                'tipo_usuario' => $datos['tipo_usuario'],
                'asunto' => trim((string) $datos['asunto']),
                'mensaje' => trim((string) $datos['mensaje']),
                'acepta_contacto' => !empty($datos['acepta_contacto']) ? 1 : 0,
            ]
        );

        $this->registrarEvento($id, null, 'creada', 'Solicitud enviada desde el formulario publico.');

        return $id;
    }

    public function listar(?string $estado = null): array
    {
        if ($estado !== null && $estado !== '') {
            return $this->todos(
                'select * from solicitudes_contacto where estado = :estado order by creada_en desc',
                ['estado' => $estado]
            );
        }

        return $this->todos('select * from solicitudes_contacto order by creada_en desc');
    }

    public function buscar(string $id): ?array
    {
        return $this->uno('select * from solicitudes_contacto where id = :id limit 1', ['id' => $id]);
    }

    public function eventos(string $id): array
    {
        return $this->todos(
            'select e.*, a.nombre as admin_nombre
               from eventos_solicitud e
          left join administradores a on a.id = e.administrador_id
              where e.solicitud_id = :id
           order by e.creado_en desc',
            ['id' => $id]
        );
    }

    public function cambiarEstado(string $id, string $estado, string $adminId, ?string $nota = null): void
    {
        $this->ejecutar(
            'update solicitudes_contacto set estado = :estado, notas_admin = :nota where id = :id',
            ['estado' => $estado, 'nota' => $nota, 'id' => $id]
        );

        $this->registrarEvento($id, $adminId, $estado, $nota ?: 'Cambio de estado registrado.');
    }

    public function vincularUsuario(string $id, string $usuarioId): void
    {
        $this->ejecutar(
            'update solicitudes_contacto set usuario_creado_id = :usuario_id where id = :id',
            ['usuario_id' => $usuarioId, 'id' => $id]
        );
    }

    public function contarPorEstado(): array
    {
        $filas = $this->todos('select estado, count(*) as total from solicitudes_contacto group by estado');
        $conteos = ['nueva' => 0, 'en_revision' => 0, 'aprobada' => 0, 'rechazada' => 0];

        foreach ($filas as $fila) {
            $conteos[$fila['estado']] = (int) $fila['total'];
        }

        return $conteos;
    }

    private function registrarEvento(string $solicitudId, ?string $adminId, string $tipo, string $detalle): void
    {
        $this->ejecutar(
            'insert into eventos_solicitud (id, solicitud_id, administrador_id, tipo, detalle)
             values (:id, :solicitud_id, :admin_id, :tipo, :detalle)',
            [
                'id' => $this->id('evt'),
                'solicitud_id' => $solicitudId,
                'admin_id' => $adminId,
                'tipo' => $tipo,
                'detalle' => $detalle,
            ]
        );
    }
}

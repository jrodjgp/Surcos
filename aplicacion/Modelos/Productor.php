<?php

declare(strict_types=1);

final class Productor extends Modelo
{
    public function todosConResumen(): array
    {
        return $this->todos(
            'select pr.*,
                    (select count(*) from pools p where p.productor_id = pr.id) as pools_total,
                    (select count(*) from pools p where p.productor_id = pr.id and p.estado = "activo" and p.fecha_cierre >= now()) as pools_activos,
                    (select min(p.fecha_cierre) from pools p where p.productor_id = pr.id and p.estado = "activo" and p.fecha_cierre >= now()) as proximo_cierre
               from productores pr
              where pr.estado = "activo"
           order by pools_activos desc, pr.nombre asc'
        );
    }

    public function buscar(string $id): ?array
    {
        return $this->uno(
            'select pr.*,
                    (select count(*) from pools p where p.productor_id = pr.id) as pools_total,
                    (select count(*) from pools p where p.productor_id = pr.id and p.estado = "activo" and p.fecha_cierre >= now()) as pools_activos
               from productores pr
              where pr.id = :id
              limit 1',
            ['id' => $id]
        );
    }

    public function buscarPorUsuario(string $usuarioId): ?array
    {
        return $this->uno(
            'select pr.*,
                    (select count(*) from pools p where p.productor_id = pr.id) as pools_total,
                    (select count(*) from pools p where p.productor_id = pr.id and p.estado = "activo" and p.fecha_cierre >= now()) as pools_activos
               from productores pr
              where pr.usuario_id = :usuario_id
              limit 1',
            ['usuario_id' => $usuarioId]
        );
    }

    public function crearDesdeSolicitud(array $solicitud, string $usuarioId): string
    {
        $id = $this->id('prod');
        $nombre = trim((string) $solicitud['nombre']);
        $asunto = trim((string) ($solicitud['asunto'] ?? ''));
        $mensaje = trim((string) ($solicitud['mensaje'] ?? ''));

        $this->ejecutar(
            'insert into productores (
                id, usuario_id, nombre, responsable, provincia, zona, especialidad, historia, estado
            ) values (
                :id, :usuario_id, :nombre, :responsable, "Panama", "Por validar",
                :especialidad, :historia, "pendiente"
            )',
            [
                'id' => $id,
                'usuario_id' => $usuarioId,
                'nombre' => $nombre,
                'responsable' => $nombre,
                'especialidad' => $asunto !== '' ? $asunto : 'Produccion agricola',
                'historia' => $mensaje !== '' ? $mensaje : 'Productor aprobado por solicitud de afiliacion.',
            ]
        );

        return $id;
    }

    public function buscarPorPool(string $poolId): ?array
    {
        return $this->uno(
            'select pr.*,
                    (select count(*) from pools px where px.productor_id = pr.id) as pools_total,
                    (select count(*) from pools px where px.productor_id = pr.id and px.estado = "activo" and px.fecha_cierre >= now()) as pools_activos
               from pools p
               join productores pr on pr.id = p.productor_id
              where p.id = :pool_id
              limit 1',
            ['pool_id' => $poolId]
        );
    }

    public function poolsActivos(string $productorId): array
    {
        return $this->todos(
            'select p.*, pr.nombre as productor_nombre, pr.responsable as productor_responsable,
                    n.nombre as nodo_nombre
               from pools p
               join productores pr on pr.id = p.productor_id
          left join nodos_retiro n on n.id = p.nodo_retiro_id
              where p.productor_id = :productor_id
                and p.estado = "activo"
                and p.fecha_cierre >= now()
           order by p.fecha_cierre asc',
            ['productor_id' => $productorId]
        );
    }

    public function relacionados(string $productorId, int $limite = 5): array
    {
        return $this->todos(
            'select pr.*,
                    (select count(*) from pools p where p.productor_id = pr.id) as pools_total,
                    (select count(*) from pools p where p.productor_id = pr.id and p.estado = "activo" and p.fecha_cierre >= now()) as pools_activos
               from productores pr
              where pr.estado = "activo"
                and pr.id <> :productor_id
           order by pools_activos desc, pr.nombre asc
              limit ' . max(1, $limite),
            ['productor_id' => $productorId]
        );
    }
}

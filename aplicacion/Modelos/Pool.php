<?php

declare(strict_types=1);

final class Pool extends Modelo
{
    public function activos(): array
    {
        return $this->todos(
            'select p.*, pr.nombre as productor_nombre, pr.responsable as productor_responsable,
                    n.nombre as nodo_nombre
               from pools p
               join productores pr on pr.id = p.productor_id
          left join nodos_retiro n on n.id = p.nodo_retiro_id
              where p.estado = "activo" and p.fecha_cierre >= now()
           order by p.fecha_cierre asc'
        );
    }

    public function todosAdmin(): array
    {
        return $this->todos(
            'select p.*, pr.nombre as productor_nombre
               from pools p
               join productores pr on pr.id = p.productor_id
           order by p.fecha_cierre desc'
        );
    }

    public function buscar(string $id): ?array
    {
        return $this->uno(
            'select p.*, pr.nombre as productor_nombre, pr.responsable as productor_responsable,
                    pr.historia as productor_historia, pr.provincia as productor_provincia,
                    pr.zona as productor_zona, pr.especialidad as productor_especialidad,
                    n.nombre as nodo_nombre
               from pools p
               join productores pr on pr.id = p.productor_id
          left join nodos_retiro n on n.id = p.nodo_retiro_id
              where p.id = :id
              limit 1',
            ['id' => $id]
        );
    }

    public function resumenMercado(): array
    {
        $fila = $this->uno(
            'select count(*) as pools,
                    count(distinct pr.provincia) as provincias,
                    coalesce(sum(case when p.estado = "activo" then 1 else 0 end), 0) as activos,
                    min(case when p.estado = "activo" and p.fecha_cierre >= now() then p.fecha_cierre end) as proximo_cierre
               from pools p
               join productores pr on pr.id = p.productor_id'
        ) ?? [];

        return [
            'pools' => (int) ($fila['activos'] ?? 0),
            'cosechas' => (int) ($fila['pools'] ?? 0),
            'provincias' => (int) ($fila['provincias'] ?? 0),
            'proximo_cierre' => $fila['proximo_cierre'] ?? null,
        ];
    }

    public function nodosActivos(): array
    {
        return $this->todos(
            'select id, provincia, nombre
               from nodos_retiro
              where activo = 1
           order by provincia asc, nombre asc'
        );
    }

    public function crearDesdeFormulario(array $datos, array $productor): string
    {
        $id = $this->id('grupo');
        $producto = trim((string) $datos['producto']);
        $variedad = trim((string) $datos['variedad']);
        $precio = (float) $datos['precioMinimo'];
        $cantidad = (float) $datos['cantidadKg'];
        $objetivo = max(5, (int) ($datos['personasObjetivo'] ?? 20));
        $fechaCierre = (string) $datos['fechaCierre'] . ' 23:59:00';
        $fechaEntrega = (string) $datos['fechaEntrega'];

        $this->ejecutar(
            'insert into pools (
                id, productor_id, producto, variedad, categoria, origen, imagen_url,
                precio_mercado, precio_grupal, unidad, personas_actuales, personas_objetivo,
                cantidad_minima, fecha_cierre, fecha_entrega, estado, modelo_entrega, nodo_retiro_id
            ) values (
                :id, :productor_id, :producto, :variedad, :categoria, :origen, null,
                :precio_mercado, :precio_grupal, :unidad, 0, :personas_objetivo,
                :cantidad_minima, :fecha_cierre, :fecha_entrega,
                "activo", :modelo_entrega, :nodo_retiro_id
            )',
            [
                'id' => $id,
                'productor_id' => $productor['id'],
                'producto' => $producto,
                'variedad' => $variedad,
                'categoria' => $datos['categoria'],
                'origen' => trim((string) $datos['ubicacion']),
                'precio_mercado' => round($precio * 1.55, 2),
                'precio_grupal' => $precio,
                'unidad' => $datos['unidad'],
                'personas_objetivo' => $objetivo,
                'cantidad_minima' => max(1, round($cantidad / $objetivo, 2)),
                'fecha_cierre' => $fechaCierre,
                'fecha_entrega' => $fechaEntrega,
                'modelo_entrega' => $datos['modeloEntrega'] ?? 'Retiro en Nodo',
                'nodo_retiro_id' => $datos['nodoRetiro'],
            ]
        );

        return $id;
    }

    public function avance(array $pool): int
    {
        $objetivo = max(1, (int) $pool['personas_objetivo']);
        return min(100, (int) round(((int) $pool['personas_actuales'] / $objetivo) * 100));
    }

    public function activosRelacionados(string $productorId, string $poolId): array
    {
        return $this->todos(
            'select p.*, pr.nombre as productor_nombre, pr.responsable as productor_responsable,
                    n.nombre as nodo_nombre
               from pools p
               join productores pr on pr.id = p.productor_id
          left join nodos_retiro n on n.id = p.nodo_retiro_id
              where p.productor_id = :productor_id
                and p.id <> :pool_id
                and p.estado = "activo"
                and p.fecha_cierre >= now()
           order by p.fecha_cierre asc',
            ['productor_id' => $productorId, 'pool_id' => $poolId]
        );
    }
}

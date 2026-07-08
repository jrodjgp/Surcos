<?php

declare(strict_types=1);

final class Pool extends Modelo
{
    public function activos(): array
    {
        return $this->enriquecerConTramos($this->todos(
            'select p.*, pr.nombre as productor_nombre, pr.responsable as productor_responsable,
                    n.nombre as nodo_nombre
               from pools p
               join productores pr on pr.id = p.productor_id
          left join nodos_retiro n on n.id = p.nodo_retiro_id
              where p.estado = "activo" and p.fecha_cierre >= now()
           order by p.fecha_cierre asc'
        ));
    }

    public function todosAdmin(?string $estado = null): array
    {
        $parametros = [];
        $filtro = '';

        if (in_array($estado, ['activo', 'cerrado', 'fallido'], true)) {
            $filtro = ' where p.estado = :estado';
            $parametros['estado'] = $estado;
        }

        return $this->enriquecerConTramos($this->todos(
            'select p.*, pr.nombre as productor_nombre, n.nombre as nodo_nombre
               from pools p
               join productores pr on pr.id = p.productor_id
          left join nodos_retiro n on n.id = p.nodo_retiro_id'
            . $filtro .
            ' order by p.fecha_cierre desc',
            $parametros
        ));
    }

    public function buscar(string $id): ?array
    {
        $pool = $this->uno(
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

        return $pool ? $this->enriquecerPool($pool) : null;
    }

    public function resumenMercado(): array
    {
        $fila = $this->uno(
            'select count(*) as pools,
                    count(distinct pr.provincia) as provincias,
                    coalesce(sum(case when p.estado = "activo" then 1 else 0 end), 0) as activos,
                    min(case when p.estado = "activo" and p.fecha_cierre >= now() then p.fecha_cierre end) as proximo_cierre,
                    coalesce(sum(case when p.estado = "activo" then p.personas_actuales else 0 end), 0) as compradores_en_pool
               from pools p
               join productores pr on pr.id = p.productor_id'
        ) ?? [];

        return [
            'pools' => (int) ($fila['activos'] ?? 0),
            'cosechas' => (int) ($fila['pools'] ?? 0),
            'provincias' => (int) ($fila['provincias'] ?? 0),
            'compradores_en_pool' => (int) ($fila['compradores_en_pool'] ?? 0),
            'proximo_cierre' => $fila['proximo_cierre'] ?? null,
        ];
    }

    public function metricasAdmin(): array
    {
        $fila = $this->uno(
            'select
                (select count(*) from pools) as total,
                (select count(*) from pools where estado = "activo" and fecha_cierre >= now()) as activos,
                (select count(*) from pools where estado = "activo" and fecha_cierre between now() and date_add(now(), interval 3 day)) as por_cerrar,
                (select count(distinct productor_id) from pools) as productores_con_pools,
                (select coalesce(sum(monto), 0) from compromisos where estado_compromiso = "confirmado") as monto_confirmado'
        ) ?? [];

        return [
            'total' => (int) ($fila['total'] ?? 0),
            'activos' => (int) ($fila['activos'] ?? 0),
            'por_cerrar' => (int) ($fila['por_cerrar'] ?? 0),
            'productores_con_pools' => (int) ($fila['productores_con_pools'] ?? 0),
            'monto_confirmado' => (float) ($fila['monto_confirmado'] ?? 0),
        ];
    }

    public function metricasProductor(string $productorId): array
    {
        $fila = $this->uno(
            'select
                (select count(*) from pools where productor_id = :productor_total) as total,
                (select count(*) from pools where productor_id = :productor_activo and estado = "activo" and fecha_cierre >= now()) as activos,
                (select coalesce(sum(c.monto), 0)
                   from compromisos c
                   join pools p on p.id = c.pool_id
                  where p.productor_id = :productor_monto
                    and c.estado_compromiso = "confirmado") as monto_confirmado,
                (select min(fecha_cierre)
                   from pools
                  where productor_id = :productor_cierre
                    and estado = "activo"
                    and fecha_cierre >= now()) as proximo_cierre',
            [
                'productor_total' => $productorId,
                'productor_activo' => $productorId,
                'productor_monto' => $productorId,
                'productor_cierre' => $productorId,
            ]
        ) ?? [];

        return [
            'total' => (int) ($fila['total'] ?? 0),
            'activos' => (int) ($fila['activos'] ?? 0),
            'monto_confirmado' => (float) ($fila['monto_confirmado'] ?? 0),
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

        $this->crearTramosIniciales($id, $precio, $objetivo);

        return $id;
    }

    public function avance(array $pool): int
    {
        $objetivo = max(1, (int) $pool['personas_objetivo']);
        return min(100, (int) round(((int) $pool['personas_actuales'] / $objetivo) * 100));
    }

    public function activosRelacionados(string $productorId, string $poolId): array
    {
        return $this->enriquecerConTramos($this->todos(
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
        ));
    }

    public function poolsProductor(string $productorId): array
    {
        return $this->enriquecerConTramos($this->todos(
            'select p.*, pr.nombre as productor_nombre, n.nombre as nodo_nombre,
                    (select count(*)
                       from compromisos c
                      where c.pool_id = p.id
                        and c.estado_compromiso = "confirmado") as compromisos_confirmados,
                    (select coalesce(sum(c.monto), 0)
                       from compromisos c
                      where c.pool_id = p.id
                        and c.estado_compromiso = "confirmado") as monto_confirmado
               from pools p
               join productores pr on pr.id = p.productor_id
          left join nodos_retiro n on n.id = p.nodo_retiro_id
              where p.productor_id = :productor_id
           order by p.fecha_cierre desc',
            ['productor_id' => $productorId]
        ));
    }

    public function retirarPorProductor(string $poolId, string $productorId): void
    {
        $pool = $this->uno(
            'select id, estado
               from pools
              where id = :id
                and productor_id = :productor_id
              limit 1',
            ['id' => $poolId, 'productor_id' => $productorId]
        );

        if (!$pool) {
            throw new RuntimeException('El pool no pertenece a este productor.');
        }

        if (($pool['estado'] ?? '') !== 'activo') {
            throw new RuntimeException('El pool ya no esta activo.');
        }

        $confirmados = $this->uno(
            'select count(*) as total
               from compromisos
              where pool_id = :pool_id
                and estado_compromiso = "confirmado"',
            ['pool_id' => $poolId]
        );

        if ((int) ($confirmados['total'] ?? 0) > 0) {
            throw new RuntimeException('No se puede retirar un pool con compromisos confirmados.');
        }

        $this->ejecutar(
            'delete from compromisos
              where pool_id = :pool_id
                and estado_compromiso = "borrador"',
            ['pool_id' => $poolId]
        );

        $this->ejecutar(
            'update pools
                set estado = "cerrado"
              where id = :id
                and productor_id = :productor_id
                and estado = "activo"',
            ['id' => $poolId, 'productor_id' => $productorId]
        );
    }

    public function tramos(string $poolId): array
    {
        return $this->todos(
            'select *
               from tramos_precio_pool
              where pool_id = :pool_id
           order by compradores_minimos asc',
            ['pool_id' => $poolId]
        );
    }

    public function cerrarVencidos(): void
    {
        $this->ejecutar('call sp_cerrar_pools_vencidos()');
    }

    private function crearTramosIniciales(string $poolId, float $precio, int $objetivo): void
    {
        $medio = max(2, (int) floor($objetivo / 2));
        $tramos = [
            ['minimo' => 1, 'precio' => $precio, 'etiqueta' => 'Precio base del pool'],
            ['minimo' => $medio, 'precio' => round($precio * 0.92, 2), 'etiqueta' => 'Precio por volumen medio'],
            ['minimo' => $objetivo, 'precio' => round($precio * 0.85, 2), 'etiqueta' => 'Precio al completar meta'],
        ];

        foreach ($tramos as $tramo) {
            $this->ejecutar(
                'insert into tramos_precio_pool (id, pool_id, compradores_minimos, precio_unitario, etiqueta)
                 values (:id, :pool_id, :compradores_minimos, :precio_unitario, :etiqueta)',
                [
                    'id' => $this->id('tramo'),
                    'pool_id' => $poolId,
                    'compradores_minimos' => $tramo['minimo'],
                    'precio_unitario' => $tramo['precio'],
                    'etiqueta' => $tramo['etiqueta'],
                ]
            );
        }
    }

    private function enriquecerConTramos(array $pools): array
    {
        $poolIds = array_column($pools, 'id');

        $tramosPorPool = $poolIds !== []
            ? $this->tramosPorIds($poolIds)
            : [];

        return array_map(
            fn (array $pool): array => $this->enriquecerPool($pool, $tramosPorPool[(string) $pool['id']] ?? []),
            $pools
        );
    }

    private function tramosPorIds(array $poolIds): array
    {
        $placeholders = implode(',', array_fill(0, count($poolIds), '?'));

        $filas = $this->todos(
            "select * from tramos_precio_pool
              where pool_id in ({$placeholders})
           order by pool_id, compradores_minimos asc",
            $poolIds
        );

        $agrupados = [];

        foreach ($filas as $fila) {
            $agrupados[(string) $fila['pool_id']][] = $fila;
        }

        return $agrupados;
    }

    private function enriquecerPool(array $pool, array $tramos = []): array
    {
        $compradoresParaPrecio = max(1, (int) $pool['personas_actuales'] + 1);
        $tramoActual = $this->tramoParaCompradores($tramos, $compradoresParaPrecio);
        $siguienteTramo = $this->siguienteTramo($tramos, $compradoresParaPrecio);

        $pool['tramos'] = $tramos;
        $pool['tramo_actual'] = $tramoActual;
        $pool['siguiente_tramo'] = $siguienteTramo;
        $pool['precio_vigente'] = (float) ($tramoActual['precio_unitario'] ?? $pool['precio_grupal']);
        $pool['faltan_siguiente_tramo'] = $siguienteTramo
            ? max(0, (int) $siguienteTramo['compradores_minimos'] - (int) $pool['personas_actuales'])
            : 0;

        return $pool;
    }

    private function tramoParaCompradores(array $tramos, int $compradores): ?array
    {
        $vigente = null;

        foreach ($tramos as $tramo) {
            if ((int) $tramo['compradores_minimos'] <= $compradores) {
                $vigente = $tramo;
            }
        }

        return $vigente;
    }

    private function siguienteTramo(array $tramos, int $compradores): ?array
    {
        foreach ($tramos as $tramo) {
            if ((int) $tramo['compradores_minimos'] > $compradores) {
                return $tramo;
            }
        }

        return null;
    }
}

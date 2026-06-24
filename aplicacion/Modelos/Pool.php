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
                    pr.historia as productor_historia, n.nombre as nodo_nombre
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

    public function crearDesdeFormulario(array $datos): string
    {
        $id = $this->id('grupo');
        $producto = trim((string) $datos['producto']);
        $variedad = trim((string) $datos['variedad']);
        $precio = (float) $datos['precioMinimo'];
        $cantidad = (float) $datos['cantidadKg'];

        $this->ejecutar(
            'insert into pools (
                id, productor_id, producto, variedad, categoria, origen, imagen_url,
                precio_mercado, precio_grupal, unidad, personas_actuales, personas_objetivo,
                cantidad_minima, fecha_cierre, fecha_entrega, estado, modelo_entrega, nodo_retiro_id
            ) values (
                :id, "prod-oasis", :producto, :variedad, "hortalizas", :origen, :imagen,
                :precio_mercado, :precio_grupal, "kg", 0, 20,
                :cantidad_minima, date_add(now(), interval 10 day), date_add(curdate(), interval 17 day),
                "activo", :modelo_entrega, "nodo-mercado-central"
            )',
            [
                'id' => $id,
                'producto' => $producto,
                'variedad' => $variedad,
                'origen' => trim((string) $datos['ubicacion']),
                'imagen' => 'https://images.unsplash.com/photo-1592924357228-91a4daadcfea?w=800&q=80&fit=crop',
                'precio_mercado' => round($precio * 1.55, 2),
                'precio_grupal' => $precio,
                'cantidad_minima' => max(1, round($cantidad / 20, 2)),
                'modelo_entrega' => $datos['modeloEntrega'] ?? 'Retiro en Nodo',
            ]
        );

        return $id;
    }

    public function avance(array $pool): int
    {
        $objetivo = max(1, (int) $pool['personas_objetivo']);
        return min(100, (int) round(((int) $pool['personas_actuales'] / $objetivo) * 100));
    }
}

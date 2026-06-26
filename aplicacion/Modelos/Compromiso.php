<?php

declare(strict_types=1);

final class Compromiso extends Modelo
{
    public function borradores(string $usuarioId): array
    {
        return $this->todos(
            'select c.*, p.fecha_cierre, p.personas_actuales, p.personas_objetivo, p.estado as estado_pool,
                    coalesce((
                        select t.precio_unitario
                          from tramos_precio_pool t
                         where t.pool_id = p.id
                           and t.compradores_minimos <= p.personas_actuales + 1
                      order by t.compradores_minimos desc
                         limit 1
                    ), p.precio_grupal) as precio_vigente,
                    coalesce((
                        select min(t.compradores_minimos)
                          from tramos_precio_pool t
                         where t.pool_id = p.id
                           and t.compradores_minimos > p.personas_actuales + 1
                    ), 0) as proximo_tramo_compradores
               from compromisos c
               join pools p on p.id = c.pool_id
              where c.usuario_id = :usuario_id
                and c.estado_compromiso = "borrador"
           order by c.creado_en desc',
            ['usuario_id' => $usuarioId]
        );
    }

    public function agregarBorrador(string $usuarioId, array $pool, float $cantidad): string
    {
        $existente = $this->uno(
            'select id from compromisos
              where usuario_id = :usuario_id and pool_id = :pool_id and estado_compromiso = "borrador"
              limit 1',
            ['usuario_id' => $usuarioId, 'pool_id' => $pool['id']]
        );

        $precioVigente = (float) ($pool['precio_vigente'] ?? $pool['precio_grupal']);
        $monto = round($cantidad * $precioVigente, 2);

        if ($existente) {
            $this->ejecutar(
                'update compromisos set cantidad = :cantidad, monto = :monto where id = :id',
                ['cantidad' => $cantidad, 'monto' => $monto, 'id' => $existente['id']]
            );

            return $existente['id'];
        }

        $id = $this->id('cmp');

        $this->ejecutar(
            'insert into compromisos (
                id, pool_id, usuario_id, producto_snapshot, origen_snapshot,
                cantidad, unidad, monto, fecha
            ) values (
                :id, :pool_id, :usuario_id, :producto, :origen,
                :cantidad, :unidad, :monto, curdate()
            )',
            [
                'id' => $id,
                'pool_id' => $pool['id'],
                'usuario_id' => $usuarioId,
                'producto' => $pool['producto'] . ' - ' . $pool['variedad'],
                'origen' => $pool['origen'],
                'cantidad' => $cantidad,
                'unidad' => $pool['unidad'],
                'monto' => $monto,
            ]
        );

        return $id;
    }

    public function quitar(string $id, string $usuarioId): void
    {
        $this->ejecutar(
            'delete from compromisos where id = :id and usuario_id = :usuario_id and estado_compromiso = "borrador"',
            ['id' => $id, 'usuario_id' => $usuarioId]
        );
    }

    public function confirmarBorradores(string $usuarioId, string $metodoPagoId): array
    {
        $this->validarBorradoresParaConfirmar($usuarioId, $metodoPagoId);

        $resultados = [];

        foreach ($this->borradores($usuarioId) as $borrador) {
            $this->ejecutar(
                'call sp_confirmar_compromiso_pool(:borrador_id, :usuario_id, :metodo_pago_id, @compromiso_id, @referencia)',
                [
                    'borrador_id' => $borrador['id'],
                    'usuario_id' => $usuarioId,
                    'metodo_pago_id' => $metodoPagoId,
                ]
            );

            $salida = $this->uno('select @compromiso_id as compromiso_id, @referencia as referencia') ?? [];
            $resultados[] = $salida;
        }

        return $resultados;
    }

    private function validarBorradoresParaConfirmar(string $usuarioId, string $metodoPagoId): void
    {
        $metodo = $this->uno(
            'select id
               from metodos_pago
              where id = :metodo_pago_id
                and usuario_id = :usuario_id
                and activo = 1
              limit 1',
            [
                'metodo_pago_id' => $metodoPagoId,
                'usuario_id' => $usuarioId,
            ]
        );

        if (!$metodo) {
            throw new RuntimeException('El metodo de pago simulado no pertenece al usuario o esta inactivo.');
        }

        $resumen = $this->uno(
            'select count(*) as total,
                    sum(case
                        when p.estado <> "activo"
                          or p.fecha_cierre < now()
                          or p.personas_actuales >= p.personas_objetivo
                        then 1 else 0
                    end) as no_disponibles
               from compromisos c
               join pools p on p.id = c.pool_id
              where c.usuario_id = :usuario_id
                and c.estado_compromiso = "borrador"',
            ['usuario_id' => $usuarioId]
        );

        if ((int) ($resumen['total'] ?? 0) === 0) {
            throw new RuntimeException('No hay borradores en la bandeja.');
        }

        if ((int) ($resumen['no_disponibles'] ?? 0) > 0) {
            throw new RuntimeException('Uno o mas pools de la bandeja ya no estan disponibles.');
        }
    }

    public function totalBorradores(string $usuarioId): float
    {
        $fila = $this->uno(
            'select coalesce(sum(c.cantidad * coalesce((
                        select t.precio_unitario
                          from tramos_precio_pool t
                         where t.pool_id = p.id
                           and t.compradores_minimos <= p.personas_actuales + 1
                      order by t.compradores_minimos desc
                         limit 1
                    ), p.precio_grupal)), 0) as total
               from compromisos c
               join pools p on p.id = c.pool_id
              where c.usuario_id = :usuario_id
                and c.estado_compromiso = "borrador"',
            ['usuario_id' => $usuarioId]
        );

        return (float) ($fila['total'] ?? 0);
    }

    public function historialUsuario(string $usuarioId): array
    {
        return $this->todos(
            'select c.*, p.fecha_cierre, p.fecha_entrega, p.productor_id,
                    pr.nombre as productor_nombre
               from compromisos c
               join pools p on p.id = c.pool_id
               join productores pr on pr.id = p.productor_id
              where c.usuario_id = :usuario_id
                and c.estado_compromiso <> "borrador"
           order by c.actualizado_en desc, c.creado_en desc',
            ['usuario_id' => $usuarioId]
        );
    }
}

<?php

declare(strict_types=1);

final class ControladorProductor extends Controlador
{
    public function panel(): void
    {
        $usuarioId = Autenticacion::requiereUsuarioActivo();
        $usuario = (new Usuario())->buscar($usuarioId);
        $productor = (new Productor())->buscarPorUsuario($usuarioId);

        if (!$usuario || ($usuario['rol'] ?? '') !== 'productor') {
            Sesion::mensajeTemporal('error', 'El panel de productor requiere una cuenta de productor.');
            redirigir('/');
        }

        if (($usuario['estado'] ?? '') !== 'activo' || !$productor || ($productor['estado'] ?? '') !== 'activo') {
            Sesion::mensajeTemporal('error', 'Tu perfil productor todavia no esta activo.');
            redirigir('/#registro-cosecha');
        }

        $poolModelo = new Pool();

        $this->vistaPublica('productor', [
            'tituloPagina' => 'Panel Productor | Surcos',
            'descripcionPagina' => 'Panel de productor para revisar pools, cierres y compromisos simulados.',
            'paginaActiva' => 'productor',
            'estilosExtra' => ['dashboard.css', 'marketplace.css', 'pulido-landing.css'],
            'usuario' => $usuario,
            'productor' => $productor,
            'metricas' => $poolModelo->metricasProductor((string) $productor['id']),
            'pools' => $poolModelo->poolsProductor((string) $productor['id']),
            'poolModelo' => $poolModelo,
        ]);
    }

    public function retirarPool(): void
    {
        $this->requierePostValido();

        $usuarioId = Autenticacion::requiereUsuarioActivo();
        $usuario = (new Usuario())->buscar($usuarioId);
        $productor = (new Productor())->buscarPorUsuario($usuarioId);

        if (!$usuario || ($usuario['rol'] ?? '') !== 'productor' || !$productor || ($productor['estado'] ?? '') !== 'activo') {
            Sesion::mensajeTemporal('error', 'Solo productores activos pueden retirar publicaciones.');
            redirigir('/productor/');
        }

        try {
            (new Pool())->retirarPorProductor((string) ($_POST['pool_id'] ?? ''), (string) $productor['id']);
            Sesion::mensajeTemporal('exito', 'Publicacion retirada del marketplace.');
        } catch (Throwable $excepcion) {
            Sesion::mensajeTemporal('error', 'No se pudo retirar la publicacion: ' . $excepcion->getMessage());
        }

        redirigir('/productor/');
    }
}

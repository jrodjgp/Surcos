<?php

declare(strict_types=1);

function cargar_archivo_entorno(string $ruta): void
{
    if (!is_file($ruta) || !is_readable($ruta)) {
        return;
    }

    $lineas = file($ruta, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lineas ?: [] as $linea) {
        $linea = trim($linea);

        if ($linea === '' || str_starts_with($linea, '#') || !str_contains($linea, '=')) {
            continue;
        }

        [$clave, $valor] = array_map('trim', explode('=', $linea, 2));
        $valor = trim($valor, "\"'");

        if ($clave !== '' && getenv($clave) === false) {
            putenv($clave . '=' . $valor);
            $_ENV[$clave] = $valor;
            $_SERVER[$clave] = $valor;
        }
    }
}

function valor_entorno(string $clave, mixed $defecto = null): mixed
{
    $valor = getenv($clave);

    if ($valor === false && array_key_exists($clave, $_ENV)) {
        $valor = $_ENV[$clave];
    }

    if ($valor === false && array_key_exists($clave, $_SERVER)) {
        $valor = $_SERVER[$clave];
    }

    if ($valor === false || $valor === '') {
        return $defecto;
    }

    return match (strtolower((string) $valor)) {
        'true' => true,
        'false' => false,
        'null' => null,
        default => $valor,
    };
}

function configuracion(?string $clave = null, mixed $defecto = null): mixed
{
    $configuracion = $GLOBALS['configuracion_surcos'] ?? [];

    if ($clave === null) {
        return $configuracion;
    }

    $valor = $configuracion;

    foreach (explode('.', $clave) as $segmento) {
        if (!is_array($valor) || !array_key_exists($segmento, $valor)) {
            return $defecto;
        }

        $valor = $valor[$segmento];
    }

    return $valor;
}

function escapar(mixed $valor): string
{
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}

function ruta_vista(string $vista): string
{
    return RUTA_APLICACION . '/Vistas/' . ltrim($vista, '/');
}

function renderizar_vista(string $vista, array $datos = []): void
{
    extract($datos, EXTR_SKIP);
    require ruta_vista($vista);
}

function ruta_actual(): string
{
    $ruta = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
    return '/' . trim($ruta, '/');
}

function url_para(string $ruta = ''): string
{
    $base = rtrim((string) configuracion('aplicacion.url', ''), '/');
    $ruta = '/' . ltrim($ruta, '/');

    return $base !== '' ? $base . $ruta : $ruta;
}

function url_recurso(string $ruta): string
{
    return url_para('recursos/' . ltrim($ruta, '/'));
}

function pagina_activa(string $ruta): bool
{
    return ruta_actual() === '/' . trim($ruta, '/');
}

function redirigir(string $ruta): never
{
    header('Location: ' . url_para($ruta));
    exit;
}

function token_csrf(): string
{
    return ProteccionCsrf::token();
}

function campo_csrf(): string
{
    return '<input type="hidden" name="_token" value="' . escapar(token_csrf()) . '" />';
}

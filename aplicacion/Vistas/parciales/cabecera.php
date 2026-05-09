<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" type="image/svg+xml" href="<?= escapar(url_recurso('img/logo-surcos-icono.svg')) ?>" />
  <title><?= escapar($tituloPagina ?? 'Surcos') ?></title>
  <meta name="description" content="<?= escapar($descripcionPagina ?? 'Surcos - Del campo al terminal.') ?>" />
  <link
    href="https://fonts.googleapis.com/css2?family=Newsreader:ital,opsz,wght@0,6..72,400;0,6..72,700;1,6..72,400&family=Space+Grotesk:wght@400;500;700&display=swap"
    rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9Oer+RExp5H92yM9LL3G8G2CkXn5c5L7+AMvyTG2x"
    crossorigin="anonymous" />
  <link rel="stylesheet" href="<?= escapar(url_recurso('css/styles.css')) ?>" />
  <link rel="stylesheet" href="<?= escapar(url_recurso('css/navbar.css')) ?>" />
  <?php foreach (($estilosExtra ?? []) as $hojaEstilos): ?>
    <link rel="stylesheet" href="<?= escapar(url_recurso('css/' . $hojaEstilos)) ?>" />
  <?php endforeach; ?>
</head>

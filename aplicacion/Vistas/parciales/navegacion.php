<?php
  $rolSesion = Sesion::obtener('usuario_rol');
  $usuarioSesion = Autenticacion::usuarioId();
?>
<body>
  <header class="navbar <?= !empty($navegacionFija) ? 'navbar--fixed' : '' ?>">
    <a class="brand" href="<?= escapar(url_para('/')) ?>"><img src="<?= escapar(url_recurso('img/logo-surcos.svg')) ?>" alt="Surcos" /></a>
    <nav class="nav-links">
      <a class="<?= ($paginaActiva ?? '') === 'marketplace' ? 'active' : '' ?>" href="<?= escapar(url_para('/')) ?>">Marketplace</a>
      <a class="<?= ($paginaActiva ?? '') === 'bandeja' ? 'active' : '' ?>" href="<?= escapar(url_para('/bandeja.php')) ?>">Bandeja</a>
      <?php if ($rolSesion === 'productor'): ?>
        <a class="<?= ($paginaActiva ?? '') === 'productor' ? 'active' : '' ?>" href="<?= escapar(url_para('/productor/')) ?>">Productor</a>
      <?php endif; ?>
      <a class="<?= ($paginaActiva ?? '') === 'historias' ? 'active' : '' ?>" href="<?= escapar(url_para('/historias_productor.php')) ?>">Historias</a>
      <a class="<?= ($paginaActiva ?? '') === 'nosotros' ? 'active' : '' ?>" href="<?= escapar(url_para('/nosotros.php')) ?>">Nosotros</a>
      <a class="<?= ($paginaActiva ?? '') === 'contacto' ? 'active' : '' ?>" href="<?= escapar(url_para('/contacto.php')) ?>">Contacto</a>
    </nav>
    <a class="nav-user <?= $usuarioSesion ? 'sesion-activa' : '' ?>" href="<?= escapar(url_para($usuarioSesion ? '/salir.php' : '/ingreso.php')) ?>" aria-label="<?= $usuarioSesion ? 'cerrar sesion' : 'ingresar a Surcos' ?>">
      <svg viewBox="0 0 24 24">
        <circle cx="12" cy="8" r="4"></circle>
        <path d="M4 20c0-4 4-7 8-7s8 3 8 7"></path>
      </svg>
      <span><?= $usuarioSesion ? 'Salir' : 'Ingresar' ?></span>
    </a>
  </header>

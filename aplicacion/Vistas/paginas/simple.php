<main class="wrap wrap--md">
  <section class="estado-vacio">
    <strong><?= escapar($titulo ?? 'Surcos') ?></strong>
    <p><?= escapar($texto ?? 'Contenido en preparacion.') ?></p>
    <a class="btn-primary" href="<?= escapar(url_para('/')) ?>">Volver al marketplace</a>
  </section>
</main>

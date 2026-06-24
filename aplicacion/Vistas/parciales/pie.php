  <footer class="footer">
    <div>
      <div class="f-brand"><img src="<?= escapar(url_recurso('img/logo-surcos-claro.svg')) ?>" alt="Surcos" /></div>
      <div class="f-small">Del campo al registro colectivo.</div>
      <div class="f-small">&copy; 2026 SURCOS. MERCADO DE POOLS.</div>
    </div>
    <div>
      <div class="f-head">Red</div>
      <a class="f-link" href="<?= escapar(url_para('/bandeja.php')) ?>">Bandeja de Pools</a>
      <a class="f-link" href="<?= escapar(url_para('/contacto.php')) ?>">Contacto</a>
      <a class="f-link" href="<?= escapar(url_para('/api/pools.php')) ?>">WS Pools</a>
      <a class="f-link f-social" href="mailto:contacto@surcos.pa">contacto@surcos.pa</a>
    </div>
    <div>
      <div class="f-head">Legal</div>
      <a class="f-link" href="<?= escapar(url_para('/contacto.php')) ?>">Privacidad</a>
      <a class="f-link" href="<?= escapar(url_para('/contacto.php')) ?>">Terminos</a>
    </div>
    <div>
      <div class="f-head">Entorno</div>
      <div class="f-small">PHP <?= escapar(PHP_VERSION) ?></div>
      <div class="f-small">BD: MySQL/MariaDB</div>
      <div class="f-small">ENTORNO: <?= escapar(strtoupper((string) configuracion('aplicacion.entorno', 'local'))) ?></div>
    </div>
  </footer>
  <?php if (($cargarBootstrapJs ?? true) === true): ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
      crossorigin="anonymous"></script>
  <?php endif; ?>
</body>

</html>

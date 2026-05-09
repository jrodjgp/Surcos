<?php

$mensajeExito = Sesion::consumirMensaje('exito');
$mensajeError = Sesion::consumirMensaje('error');

?>
<?php if ($mensajeExito): ?>
  <div class="alert alert-success" role="status"><?= escapar($mensajeExito) ?></div>
<?php endif; ?>

<?php if ($mensajeError): ?>
  <div class="alert alert-danger" role="alert"><?= escapar($mensajeError) ?></div>
<?php endif; ?>

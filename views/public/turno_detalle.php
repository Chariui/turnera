<?php if (!empty($turno)): ?>
  <div class="card shadow p-3">
    <h4>Detalle del turno</h4>
    <ul class="list-group mt-2">
      <li class="list-group-item"><strong>Código:</strong> <?= htmlspecialchars($turno['codigo']) ?></li>
      <li class="list-group-item"><strong>Nombre:</strong> <?= htmlspecialchars($turno['nombre']) ?></li>
      <li class="list-group-item"><strong>DNI:</strong> <?= htmlspecialchars($turno['dni']) ?></li>
      <li class="list-group-item"><strong>Servicio:</strong> <?= htmlspecialchars($turno['servicio']) ?></li>
      <li class="list-group-item"><strong>Fecha / Hora:</strong> <?= htmlspecialchars($turno['fecha']) ?> <?= htmlspecialchars($turno['hora']) ?></li>
      <li class="list-group-item"><strong>Estado:</strong> <?= htmlspecialchars($turno['estado']) ?></li>
    </ul>

    <?php if ($turno['estado'] !== 'cancelado'): ?>
      <form method="POST" action="index.php?action=cancelarTurno" class="mt-3" onsubmit="return confirmarCancelacion()">
        <input type="hidden" name="codigo" value="<?= htmlspecialchars($turno['codigo']) ?>">
        <button type="submit" class="btn btn-danger">Cancelar turno</button>
      </form>

      <script>
        function confirmarCancelacion() {
          return confirm("¿Está seguro que desea cancelar su turno?");
        }
      </script>
    <?php endif; ?>

    <a href="index.php" class="btn btn-secondary mt-3">Volver</a>
  </div>
<?php else: ?>
  <div class="alert alert-warning">No se encontró ningún turno con ese código.</div>
  <a href="index.php" class="btn btn-secondary">Volver</a>
<?php endif; ?>
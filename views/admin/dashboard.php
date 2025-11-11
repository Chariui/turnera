<h2 class="mb-4 text-center">Panel de Administración</h2>

<div class="card shadow-sm">
  <div class="card-body">
    <h5 class="mb-3">Listado de turnos registrados</h5>

    <?php if (!empty($turnos)): ?>
      <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
          <thead class="table-primary text-center">
            <tr>
              <th>Código</th>
              <th>Nombre</th>
              <th>DNI</th>
              <th>Servicio</th>
              <th>Fecha</th>
              <th>Hora</th>
              <th>Estado</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($turnos as $t): ?>
              <tr>
                <td class="text-center"><?= htmlspecialchars($t['codigo']) ?></td>
                <td><?= htmlspecialchars($t['nombre']) ?></td>
                <td><?= htmlspecialchars($t['dni']) ?></td>
                <td><?= htmlspecialchars($t['servicio']) ?></td>
                <td><?= htmlspecialchars($t['fecha']) ?></td>
                <td><?= htmlspecialchars($t['hora']) ?></td>
                <td><?= htmlspecialchars($t['estado']) ?></td>
                <td class="text-center">
                  <form method="POST" action="index.php?action=adminAtendido" style="display:inline;">
                    <input type="hidden" name="codigo" value="<?= $t['codigo'] ?>">
                    <button class="btn btn-success btn-sm" onclick="return confirm('Marcar como atendido?')">Atendido</button>
                  </form>
                  <form method="POST" action="index.php?action=adminCancelar" style="display:inline;">
                    <input type="hidden" name="codigo" value="<?= $t['codigo'] ?>">
                    <button class="btn btn-warning btn-sm" onclick="return confirm('Cancelar turno?')">Cancelar</button>
                  </form>
                  <form method="POST" action="index.php?action=adminEliminar" style="display:inline;">
                    <input type="hidden" name="codigo" value="<?= $t['codigo'] ?>">
                    <button class="btn btn-danger btn-sm" onclick="return confirm('Eliminar turno?')">Eliminar</button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <div class="alert alert-info text-center">No hay turnos registrados aún.</div>
    <?php endif; ?>
  </div>
</div>

<div class="mt-4 text-center">
  <a href="index.php" class="btn btn-outline-primary">Volver al inicio</a>
</div>
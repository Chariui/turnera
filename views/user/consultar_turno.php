<div class="card shadow p-3">
  <h4>Mis turnos</h4>
  <?php if (!empty($turnos)): ?>
    <table class="table mt-3">
      <thead class="table-light">
        <tr>
          <th>Código</th><th>Servicio</th><th>Fecha</th><th>Hora</th><th>Estado</th><th>Acción</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($turnos as $t): ?>
          <tr>
            <td><?= htmlspecialchars($t['codigo']) ?></td>
            <td><?= htmlspecialchars($t['servicio']) ?></td>
            <td><?= htmlspecialchars($t['fecha']) ?></td>
            <td><?= htmlspecialchars($t['hora']) ?></td>
            <td><?= htmlspecialchars($t['estado']) ?></td>
            <td>
              <?php if ($t['estado'] !== 'cancelado'): ?>
                <form method="POST" action="index.php?action=cancelarTurno" style="display:inline;">
                  <input type="hidden" name="codigo" value="<?= $t['codigo'] ?>">
                  <button class="btn btn-danger btn-sm" onclick="return confirm('Cancelar este turno?')">Cancelar</button>
                </form>
              <?php else: ?>
                -
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php else: ?>
    <div class="alert alert-info">No tenés turnos.</div>
  <?php endif; ?>
</div>
<!-- views/auth/login.php -->
<!--
Vista de inicio de sesión de usuarios.
Envía los datos al controlador principal usando action=loginProcess.
-->

<div class="card mx-auto shadow" style="max-width: 400px;">
  <div class="card-body">
    <h4 class="card-title text-center mb-3">Iniciar sesión</h4>

    <!-- ✅ Mensaje flash (por ejemplo: "Debe iniciar sesión para sacar un turno") -->
    <?php if (!empty($_SESSION['flash'])): ?>
      <div class="alert alert-warning text-center">
        <?= htmlspecialchars($_SESSION['flash']) ?>
      </div>
      <?php unset($_SESSION['flash']); ?> <!-- se borra para no repetir -->
    <?php endif; ?>

    <!-- Mensajes de error o éxito -->
    <?php if (!empty($error)): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
      <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <!-- Formulario de login -->
    <form method="POST" action="index.php?action=loginProcess">
      <div class="mb-3">
        <label class="form-label">Usuario</label>
        <input type="text" name="usuario" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Contraseña</label>
        <input type="password" name="password" class="form-control" required>
      </div>

      <div class="d-grid">
        <button type="submit" class="btn btn-primary">Ingresar</button>
      </div>
    </form>

    <div class="text-center mt-3">
      <a href="index.php?action=register">¿No tienes cuenta? Crear cuenta</a>
    </div>
  </div>
</div>
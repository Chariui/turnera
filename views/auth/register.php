<!-- views/auth/register.php -->
<!--
Vista de registro de nuevos usuarios.
Envía los datos al controlador principal usando action=registerProcess.
-->

<div class="card mx-auto shadow" style="max-width: 400px;">
  <div class="card-body">
    <h4 class="card-title text-center mb-3">Crear cuenta</h4>

    <!-- Mostramos mensajes de error si existen -->
    <?php if (!empty($error)): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <!-- Mostramos mensaje de éxito si el registro fue correcto -->
    <?php if (!empty($success)): ?>
      <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <!-- Formulario de registro -->
    <!-- ✅ Enviamos los datos al método registerProcess del AuthController -->
    <form method="POST" action="index.php?action=registerProcess">
      <div class="mb-3">
        <label class="form-label">Usuario</label>
        <input type="text" name="usuario" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Contraseña</label>
        <input type="password" name="password" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Confirmar contraseña</label>
        <input type="password" name="confirmar" class="form-control" required>
      </div>

      <div class="d-grid">
        <button type="submit" class="btn btn-primary">Registrar</button>
      </div>
    </form>

    <!-- Enlace para volver al login -->
    <div class="text-center mt-3">
      <a href="index.php?action=login">¿Ya tienes cuenta? Iniciar sesión</a>
    </div>
  </div>
</div>
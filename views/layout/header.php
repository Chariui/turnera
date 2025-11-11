<?php
// Si la sesión no está iniciada, la iniciamos
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Turnera - Sistema de Turnos</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- ✅ Ruta corregida del CSS para evitar pérdida de estilos al usar ?action= -->
  <link rel="stylesheet" href="/proyectosutn/turnera/css/style.css">
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
    <div class="container">
      <!-- Nombre del sitio -->
      <a class="navbar-brand fw-bold" href="index.php">Turnera Medica Chacabuco</a>

      <div>
        <?php if (!empty($_SESSION['user'])): ?>
          <!-- Si el usuario está logueado, mostramos su nombre -->
          <span class="text-light me-2">
            Hola, <?= htmlspecialchars($_SESSION['user'] ?? '') ?>
          </span>

          <!-- Si es admin, mostramos el botón de panel -->
          <?php if (!empty($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
            <a href="index.php?action=dashboard" class="btn btn-outline-light btn-sm me-2">Panel Admin</a>
          <?php endif; ?>

          <!-- Botón para cerrar sesión -->
          <a href="index.php?action=logout" class="btn btn-light btn-sm">Salir</a>

        <?php else: ?>
          <!-- Si NO está logueado, mostramos botones de login y registro -->
          <a href="index.php?action=login" class="btn btn-light btn-sm me-2">Iniciar sesión</a>
          <a href="index.php?action=register" class="btn btn-outline-light btn-sm">Crear cuenta</a>
        <?php endif; ?>
      </div>
    </div>
  </nav>

  <div class="container">

  <!-- ✅ Incluimos Bootstrap JS (si no lo cargas en footer.php) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?php
// index.php
// Controlador principal del sistema de turnos.
// Se encarga de enrutar las acciones según la variable ?action= en la URL.

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Iniciamos sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) session_start();

// Cargamos los controladores principales
require_once __DIR__ . "/controllers/TurnoController.php";
require_once __DIR__ . "/controllers/AuthController.php";

// Instanciamos los controladores
$turnoCtrl = new TurnoController();
$authCtrl = new AuthController();

// Determinamos la acción actual (por defecto 'home')
$action = $_GET['action'] ?? 'home';

switch ($action) {
    // -------------------------------------------------
    // SECCIÓN PÚBLICA
    // -------------------------------------------------
    case 'home':
        $turnoCtrl->home();
        break;
    case 'sacarTurno':
        $turnoCtrl->sacarTurno();
        break;
    case 'consultarTurno':
        $turnoCtrl->consultarTurno();
        break;
    case 'cancelarTurno':
        $turnoCtrl->cancelarTurno();
        break;

    // -------------------------------------------------
    // SECCIÓN DE AUTENTICACIÓN
    // -------------------------------------------------
    case 'login': // Muestra el formulario de inicio de sesión
        $authCtrl->login();
        break;

    case 'logout': // Cierra la sesión
        $authCtrl->logout();
        break;

    case 'register': // Muestra el formulario de registro
        $authCtrl->register();
        break;

    case 'registerProcess': // ✅ Procesa el formulario de registro
        $authCtrl->registerProcess();
        break;

    case 'loginProcess': // (opcional si no lo tenías)
        $authCtrl->loginProcess();
        break;

    // -------------------------------------------------
    // SECCIÓN ADMINISTRATIVA
    // -------------------------------------------------
    case 'dashboard':
        $turnoCtrl->dashboard();
        break;
    case 'adminCancelar':
        $turnoCtrl->adminCancelar();
        break;
    case 'adminAtendido':
        $turnoCtrl->adminAtendido();
        break;
    case 'adminEliminar':
        $turnoCtrl->adminEliminar();
        break;

    // -------------------------------------------------
    // POR DEFECTO
    // -------------------------------------------------
    default:
        $turnoCtrl->home();
        break;
}
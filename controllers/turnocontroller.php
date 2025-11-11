<?php
require_once __DIR__ . "/../models/Turno.php"; // Incluye el modelo Turno para usar sus métodos
require_once __DIR__ . "/../models/User.php"; // Incluye el modelo User para usar sus métodos

if (session_status() === PHP_SESSION_NONE) session_start(); // Inicia sesión si no está iniciada

class TurnoController {
    private $turnoModel; // Propiedad para almacenar instancia del modelo Turno
    private $userModel; // Propiedad para almacenar instancia del modelo User

    public function __construct() {
        $this->turnoModel = new Turno(); // Crea una instancia del modelo Turno
        $this->userModel = new User(); // Crea una instancia del modelo User
    }

    private function render($view, $data = []) { // Método para renderizar vistas con header y footer
        extract($data); // Extrae los datos para usarlos en la vista
        include __DIR__ . "/../views/layout/header.php"; // Incluye header
        include __DIR__ . "/../views/{$view}"; // Incluye la vista principal
        include __DIR__ . "/../views/layout/footer.php"; // Incluye footer
    }

    // Muestra la página principal con los turnos del usuario logueado
    public function home() {
        $turnos_usuario = []; // Inicializa array de turnos
        if (isset($_SESSION['user_id'])) { // Si el usuario está logueado
            $turnos_usuario = $this->turnoModel->obtenerPorUsuarioId($_SESSION['user_id']); // Obtiene sus turnos
        }
        $this->render("public/home.php", ['turnos_usuario' => $turnos_usuario]); // Renderiza la vista home con los turnos
    }

    // Método para sacar un turno
    public function sacarTurno() {
        if (!isset($_SESSION['user_id'])) { // Verifica que el usuario esté logueado
            $_SESSION['flash'] = "Debe iniciar sesión para sacar un turno."; // Mensaje flash
            header("Location: index.php?action=login"); // Redirige al login
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { // Verifica que sea POST
            header("Location: index.php"); // Redirige a home
            exit;
        }

        $nombre = trim($_POST['nombre'] ?? ''); // Obtiene nombre del formulario
        $dni = trim($_POST['dni'] ?? ''); // Obtiene dni
        $servicio = trim($_POST['servicio'] ?? ''); // Obtiene servicio
        $fecha = $_POST['fecha'] ?? ''; // Obtiene fecha
        $hora = $_POST['hora'] ?? ''; // Obtiene hora
        $usuario_id = $_SESSION['user_id']; // ID del usuario logueado

        $codigo = $this->turnoModel->crear($nombre, $dni, $servicio, $fecha, $hora, $usuario_id); // Crea turno
        if ($codigo) { // Si se creó correctamente
            $this->render("public/turno_exito.php", ['codigo' => $codigo]); // Muestra vista de éxito
        } else {
            echo "Error creando turno"; // Muestra error
        }
    }

    // Método para consultar un turno por código
    public function consultarTurno() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { // Verifica POST
            header("Location: index.php");
            exit;
        }
        $codigo = trim($_POST['codigo'] ?? ''); // Obtiene código del formulario
        $turno = $this->turnoModel->obtenerPorCodigo($codigo); // Busca turno por código
        $this->render("public/turno_detalle.php", ['turno' => $turno]); // Muestra detalle del turno
    }

    // Método para que un usuario cancele su turno
    public function cancelarTurno() {
        if (!isset($_SESSION['user_id'])) { // Verifica sesión
            $_SESSION['flash'] = "Debe iniciar sesión para cancelar un turno.";
            header("Location: index.php?action=login");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { // Verifica método POST
            header("Location: index.php");
            exit;
        }

        $codigo = trim($_POST['codigo'] ?? ''); // Obtiene código
        $turno = $this->turnoModel->obtenerPorCodigo($codigo); // Busca turno
        if (!$turno) { // Si no existe
            $msg = "No se encontró el turno con ese código.";
            $this->render("public/turno_cancelado.php", ['msg' => $msg]);
            return;
        }

        if (!empty($turno['usuario_id']) && $turno['usuario_id'] != $_SESSION['user_id']) { // Si no le pertenece
            $msg = "No podés cancelar un turno que no te pertenece.";
            $this->render("public/turno_cancelado.php", ['msg' => $msg]);
            return;
        }

        $this->turnoModel->cancelarPorCodigo($codigo); // Cancela turno
        $msg = "El turno fue cancelado correctamente."; // Mensaje éxito
        $this->render("public/turno_cancelado.php", ['msg' => $msg]); // Muestra vista
    }

    // Panel de administrador
    public function dashboard() {
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') { // Verifica rol admin
            $_SESSION['flash'] = "Debe ser administrador para acceder al panel.";
            header("Location: index.php?action=login");
            exit;
        }

        $turnos = $this->turnoModel->obtenerTodos(); // Obtiene todos los turnos
        $this->render("admin/dashboard.php", ['turnos' => $turnos]); // Renderiza dashboard
    }

    // Admin cancela turno de cualquier usuario
    public function adminCancelar() {
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') { // Verifica admin
            $this->render("admin/error.php"); // Muestra error si no es admin
            return;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') { // Solo POST
            $codigo = $_POST['codigo'] ?? ''; // Obtiene código
            $this->turnoModel->cancelarPorCodigo($codigo); // Cancela turno
        }
        header("Location: index.php?action=dashboard"); // Redirige dashboard
    }

    // Admin marca un turno como atendido
    public function adminAtendido() {
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') { // Verifica admin
            $this->render("admin/error.php");
            return;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') { // Solo POST
            $codigo = $_POST['codigo'] ?? '';
            $this->turnoModel->marcarAtendido($codigo); // Marca como atendido
        }
        header("Location: index.php?action=dashboard"); // Redirige dashboard
    }

    // Admin elimina un turno
    public function adminEliminar() {
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') { // Verifica admin
            $this->render("admin/error.php");
            return;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') { // Solo POST
            $codigo = $_POST['codigo'] ?? '';
            $this->turnoModel->eliminarPorCodigo($codigo); // Elimina turno
        }
        header("Location: index.php?action=dashboard"); // Redirige dashboard
    }

    // Muestra los turnos del usuario logueado
    public function misTurnos() {
        if (!isset($_SESSION['user_id'])) { // Verifica sesión
            $_SESSION['flash'] = "Debe iniciar sesión para ver sus turnos.";
            header("Location: index.php?action=login");
            exit;
        }

        $usuario_id = $_SESSION['user_id']; // ID del usuario
        $turnos = $this->turnoModel->obtenerPorUsuarioId($usuario_id); // Obtiene sus turnos

        $this->render("public/home.php", ['turnos_usuario' => $turnos]); // Renderiza home con sus turnos
    }
}
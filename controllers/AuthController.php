<?php
// controllers/AuthController.php
// Controlador de autenticación: gestiona login, registro y logout de usuarios.
// --------------------------------------------------

// Incluye la configuración de la base de datos
require_once __DIR__ . '/../config/db.php';
// Incluye el modelo User, que maneja operaciones sobre la tabla de usuarios
require_once __DIR__ . '/../models/User.php';

class AuthController
{
    private $usuarioModel; // Instancia del modelo User

    // --------------------------------------------------
    // Constructor: inicializa la conexión a la base de datos y el modelo de usuario
    // También inicia la sesión si no está iniciada
    // --------------------------------------------------
    public function __construct()
    {
        $database = new Database();          // Crea un objeto Database
        $db = $database->getConnection();    // Obtiene la conexión PDO
        $this->usuarioModel = new User($db); // Crea el modelo User con la conexión

        if (session_status() === PHP_SESSION_NONE) session_start(); // Inicia sesión si no hay ninguna activa
    }

    // --------------------------------------------------
    // Helper: renderiza una vista con header y footer
    // --------------------------------------------------
    private function render($view, $data = [])
    {
        extract($data); // Extrae las variables del array para usarlas en la vista
        include __DIR__ . '/../views/layout/header.php'; // Incluye el header
        include __DIR__ . "/../views/{$view}";           // Incluye la vista solicitada
        include __DIR__ . '/../views/layout/footer.php'; // Incluye el footer
    }

    // --------------------------------------------------
    // LOGIN (formulario)
    // Muestra la vista de login y mensajes según parámetros en la URL
    // --------------------------------------------------
    public function login()
    {
        $msg = ""; // Variable para mensajes de alerta
        if (!empty($_GET['msg'])) { // Si hay un mensaje en la URL
            switch ($_GET['msg']) {
                case 'login_required':
                    $msg = "⚠️ Debe iniciar sesión para continuar.";
                    break;
                case 'register_required':
                    $msg = "ℹ️ Cree una cuenta antes de continuar.";
                    break;
                case 'logout_success':
                    $msg = "✅ Sesión cerrada correctamente.";
                    break;
            }
        }

        // Renderiza la vista de login con el mensaje correspondiente
        $this->render('auth/login.php', ['msg' => $msg]);
    }

    // --------------------------------------------------
    // LOGIN (procesa formulario)
    // Valida usuario y contraseña, inicia sesión y redirige
    // --------------------------------------------------
    public function loginProcess()
    {
        $usuario = trim($_POST['usuario'] ?? '');   // Obtiene el nombre de usuario del POST
        $password = trim($_POST['password'] ?? ''); // Obtiene la contraseña del POST

        if (empty($usuario) || empty($password)) { // Valida que ambos campos estén completos
            $error = "Por favor, complete todos los campos.";
            $this->render('auth/login.php', ['error' => $error]); // Muestra error
            return;
        }

        $user = $this->usuarioModel->obtenerPorUsuario($usuario); // Busca usuario en DB

        if ($user && password_verify($password, $user['password'])) { // Verifica contraseña
            $_SESSION['user'] = $user['usuario']; // Guarda usuario en sesión
            $_SESSION['rol'] = $user['rol'];      // Guarda rol en sesión
            $_SESSION['user_id'] = $user['id'] ?? null; // Guarda id de usuario

            if (!empty($user['rol']) && $user['rol'] === 'admin') { // Si es admin
                header('Location: index.php?action=dashboard'); // Redirige al dashboard
            } else {
                header('Location: index.php'); // Redirige al inicio
            }
            exit;
        } else { // Si usuario o contraseña son incorrectos
            $error = "Usuario o contraseña incorrectos.";
            $this->render('auth/login.php', ['error' => $error]); // Muestra error
        }
    }

    // --------------------------------------------------
    // LOGOUT
    // Destruye la sesión y redirige al login
    // --------------------------------------------------
    public function logout()
    {
        session_unset();  // Elimina todas las variables de sesión
        session_destroy(); // Destruye la sesión
        header('Location: index.php?action=login&msg=logout_success'); // Redirige al login con mensaje
        exit;
    }

    // --------------------------------------------------
    // REGISTRO
    // Muestra el formulario de registro
    // --------------------------------------------------
    public function register()
    {
        $this->render('auth/register.php'); // Renderiza vista de registro
    }

    // --------------------------------------------------
    // Procesar registro
    // Valida datos, crea usuario en la base de datos y muestra mensajes
    // --------------------------------------------------
    public function registerProcess()
    {
        $usuario = trim($_POST['usuario'] ?? '');       // Nombre de usuario
        $password = trim($_POST['password'] ?? '');     // Contraseña
        $confirm  = trim($_POST['confirmar'] ?? '');    // Confirmación de contraseña
        $rol = 'usuario';                               // Rol por defecto

        // Validación de campos vacíos
        if (empty($usuario) || empty($password) || empty($confirm)) {
            $error = "Por favor, complete todos los campos.";
            $this->render('auth/register.php', ['error' => $error]);
            return;
        }

        // Validación de coincidencia de contraseñas
        if ($password !== $confirm) {
            $error = "Las contraseñas no coinciden.";
            $this->render('auth/register.php', ['error' => $error]);
            return;
        }

        // Validación de existencia de usuario
        if ($this->usuarioModel->obtenerPorUsuario($usuario)) {
            $error = "El nombre de usuario ya está registrado.";
            $this->render('auth/register.php', ['error' => $error]);
            return;
        }

        // Crea el usuario en la base de datos
        $ok = $this->usuarioModel->crear($usuario, $password, $rol);

        if ($ok) { // Si se creó correctamente
            $success = "✅ Registro exitoso. Ahora puede iniciar sesión.";
            $this->render('auth/login.php', ['success' => $success]);
        } else {   // Si hubo un error al crear el usuario
            $error = "No se pudo crear el usuario. Intente nuevamente.";
            $this->render('auth/register.php', ['error' => $error]);
        }
    }
}
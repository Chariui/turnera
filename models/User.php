<?php
// models/User.php
// -------------------------------------------------------------
// Modelo User: maneja las operaciones de base de datos
// relacionadas con los usuarios (crear, obtener, verificar).
// -------------------------------------------------------------

require_once __DIR__ . "/../config/db.php";

class User {
    private $conn;               // Conexión PDO a la base de datos
    private $table = "usuarios"; // Nombre de la tabla (asegúrate que exista)

    // ---------------------------------------------------------
    // Constructor: establece la conexión con la base de datos
    // ---------------------------------------------------------
    public function __construct() {
        $db = new Database();                  // Instancia la clase Database definida en config/db.php
        $this->conn = $db->getConnection();    // Obtiene la conexión PDO activa
    }

    // ---------------------------------------------------------
    // obtenerPorUsuario($usuario)
    // Devuelve un array asociativo con los datos del usuario
    // o false si no existe.
    // ---------------------------------------------------------
    public function obtenerPorUsuario($usuario) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE usuario = ? LIMIT 1");
        $stmt->execute([$usuario]);
        return $stmt->fetch(PDO::FETCH_ASSOC); // Devuelve array o false
    }

    // ---------------------------------------------------------
    // crear($usuario, $password, $rol)
    // Crea un nuevo usuario con contraseña cifrada y rol.
    // Devuelve true si se insertó correctamente, false si falló.
    // ---------------------------------------------------------
    public function crear($usuario, $password, $rol = 'usuario') {
        try {
            // Hash seguro de la contraseña
            $hash = password_hash($password, PASSWORD_DEFAULT);

            // Inserción preparada
            $stmt = $this->conn->prepare("INSERT INTO {$this->table} (usuario, password, rol) VALUES (?, ?, ?)");
            return $stmt->execute([$usuario, $hash, $rol]);

        } catch (PDOException $e) {
            // Muestra error solo en modo debug
            error_log("Error al crear usuario: " . $e->getMessage());
            return false;
        }
    }

    // ---------------------------------------------------------
    // verificarLogin($usuario, $password)
    // Verifica las credenciales:
    // - Busca el usuario
    // - Comprueba la contraseña cifrada
    // Devuelve los datos del usuario si es correcto o false si falla.
    // ---------------------------------------------------------
    public function verificarLogin($usuario, $password) {
        $u = $this->obtenerPorUsuario($usuario);   // Busca usuario
        if ($u && password_verify($password, $u['password'])) {
            return $u; // Si coincide, devuelve datos del usuario
        }
        return false; // Si no, false
    }
}
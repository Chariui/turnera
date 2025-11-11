<?php
// models/Turno.php
// Modelo Turno: maneja la lógica de base de datos para los turnos.

require_once __DIR__ . "/../config/db.php"; // Incluye la configuración de la base de datos

class Turno {
    private $conn; // Propiedad para almacenar la conexión PDO
    private $table = "turnos"; // Nombre de la tabla en la base de datos

    public function __construct() {
        $db = new Database(); // Crea instancia de la clase Database
        $this->conn = $db->getConnection(); // Obtiene la conexión PDO
    }

    // Crear un turno y devolver el código único
    public function crear($nombre, $dni, $servicio, $fecha, $hora, $usuario_id = null) {
        $pref = "TUR-" . date("Ymd") . "-"; // Prefijo del código con fecha actual
        do {
            $rand = str_pad(rand(1, 9999), 4, "0", STR_PAD_LEFT); // Genera número aleatorio de 4 dígitos
            $codigo = $pref . $rand; // Forma el código final
            $q = $this->conn->prepare("SELECT id FROM {$this->table} WHERE codigo = ?"); // Consulta para ver si el código existe
            $q->execute([$codigo]); // Ejecuta consulta
            $exists = $q->fetch(PDO::FETCH_ASSOC); // Obtiene resultado
        } while ($exists); // Repite hasta que el código sea único

        $stmt = $this->conn->prepare("INSERT INTO {$this->table} (codigo, nombre, dni, servicio, fecha, hora, estado, usuario_id)
                                      VALUES (?, ?, ?, ?, ?, ?, 'pendiente', ?)"); // Inserta turno en DB
        $ok = $stmt->execute([$codigo, $nombre, $dni, $servicio, $fecha, $hora, $usuario_id]); // Ejecuta la inserción
        return $ok ? $codigo : false; // Devuelve el código si se creó correctamente, sino false
    }

    // Obtener un turno por su código
    public function obtenerPorCodigo($codigo) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE codigo = ?"); // Prepara consulta
        $stmt->execute([$codigo]); // Ejecuta consulta
        return $stmt->fetch(PDO::FETCH_ASSOC); // Devuelve el turno como array asociativo
    }

    // Cancelar un turno cambiando su estado
    public function cancelarPorCodigo($codigo) {
        $stmt = $this->conn->prepare("UPDATE {$this->table} SET estado = 'cancelado' WHERE codigo = ?"); // Cambia estado a cancelado
        return $stmt->execute([$codigo]); // Ejecuta actualización y devuelve true/false
    }

    // Obtener todos los turnos, ordenados por fecha descendente y hora ascendente
    public function obtenerTodos() {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} ORDER BY fecha DESC, hora ASC"); // Consulta todos los turnos
        $stmt->execute(); // Ejecuta consulta
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Devuelve array de turnos
    }

    // ✅ Obtener turnos del usuario logueado, excluyendo los cancelados
    public function obtenerPorUsuarioId($usuario_id) {
        if (!$usuario_id) return []; // Evita error si no hay sesión iniciada

        $stmt = $this->conn->prepare("
            SELECT * FROM {$this->table}
            WHERE usuario_id = ?        // Filtra por usuario
              AND estado != 'cancelado' // Excluye turnos cancelados
            ORDER BY fecha DESC, hora DESC // Ordena por fecha y hora descendente
        ");
        $stmt->execute([$usuario_id]); // Ejecuta consulta
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Devuelve array de turnos
    }

    // Marcar un turno como atendido
    public function marcarAtendido($codigo) {
        $stmt = $this->conn->prepare("UPDATE {$this->table} SET estado = 'atendido' WHERE codigo = ?"); // Cambia estado a atendido
        return $stmt->execute([$codigo]); // Ejecuta y devuelve true/false
    }

    // Eliminar un turno de la base de datos
    public function eliminarPorCodigo($codigo) {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE codigo = ?"); // Elimina turno
        return $stmt->execute([$codigo]); // Ejecuta y devuelve true/false
    }
}
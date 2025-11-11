<?php
// Definición de la clase Database, que se encarga de manejar la conexión a la base de datos MySQL.
class Database
{
    // Propiedades privadas que almacenan los datos de conexión.
    private $host = "localhost";  // Dirección del servidor de base de datos (localhost si está en la misma máquina)
    private $db_name = "turnera"; // Nombre de la base de datos que se va a usar
    private $username = "root";   // Usuario de la base de datos
    private $password = "";       // Contraseña del usuario de la base de datos (vacía por defecto en entornos locales)
    public $conn;                 // Propiedad pública que almacenará la conexión PDO activa

    // Método que devuelve la conexión a la base de datos
    public function getConnection()
    {
        $this->conn = null; // Inicializa la conexión como nula por defecto

        try {
            // Crea una nueva conexión PDO con los datos configurados arriba
            $this->conn = new PDO(
                "mysql:host={$this->host};dbname={$this->db_name}", // DSN con el host y nombre de la base de datos
                $this->username, // Usuario de la base de datos
                $this->password  // Contraseña de la base de datos
            );

            // Configura el modo de errores de PDO para que lance excepciones en caso de fallo
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Configura el conjunto de caracteres a UTF-8 (soporta emojis, acentos, etc.)
            $this->conn->exec("SET NAMES utf8mb4");

        } catch (PDOException $exception) {
            // Captura cualquier error que ocurra al intentar conectarse y lo muestra
            echo "Error de conexión: " . $exception->getMessage();
        }

        // Devuelve la conexión activa (o null si falló)
        return $this->conn;
    }
}
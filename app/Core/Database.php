
<?php
// On doit remonter de deux niveaux pour sortir de 'Core' et de 'app', puis entrer dans 'config'
require_once __DIR__ . '/../../config/config.php'; 

// ... le reste de ton code Database
class Database {
    private $host = "localhost";
    private $db_name = "mon_portfolio";
    private $username = "root";
    private $password = "amadou";
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->exec("set names utf8");
        } catch(PDOException $exception) {
            echo "Erreur de connexion : " . $exception->getMessage();
        }
        return $this->conn;
    }
}
?> 
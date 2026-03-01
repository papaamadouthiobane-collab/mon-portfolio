<?php
class Message {
    private $conn;
    private $table_name = "messages";

    public $nom;
    public $email;
    public $contenu;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " (nom, email, contenu) VALUES (:nom, :email, :contenu)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":nom", $this->nom);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":contenu", $this->contenu);

        return $stmt->execute();
    }
    public function findAll() {
    $query = "SELECT * FROM " . $this->table_name . " ORDER BY id DESC";
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC); // Retourne un tableau propre
}
} 


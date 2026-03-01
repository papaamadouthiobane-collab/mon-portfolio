<?php
// On inclut les fichiers nécessaires en remontant vers la racine
require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Models/Message.php';

class ContactController {
   public function submitForm() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $database = new Database();
        $db = $database->getConnection();
        $model = new Message($db);

        $model->nom = htmlspecialchars($_POST['nom']); 
        $model->email = htmlspecialchars($_POST['email']);
        $model->contenu = htmlspecialchars($_POST['message']); 

        if ($model->create()) {
            // On répond juste "OK" pour que le JavaScript sache que c'est bon
            echo "success";
        } else {
            echo "error";
        }
        exit(); // Très important pour ne pas charger le reste de la page
    }
}
}

$controller = new ContactController();
$controller->submitForm();
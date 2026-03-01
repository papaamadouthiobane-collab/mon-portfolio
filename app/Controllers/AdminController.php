<?php
session_start(); // Permet à PHP de se souvenir que tu es connecté

require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Models/Message.php';

class AdminController {
    
    // MODIFIE LE MOT DE PASSE ICI
    private $password_secret = "mon_portfolio_2024"; 

    public function index() {
        // 1. Si on reçoit le formulaire de connexion
        if (isset($_POST['admin_password'])) {
            if ($_POST['admin_password'] === $this->password_secret) {
                $_SESSION['is_admin'] = true;
            } else {
                $error = "Mot de passe incorrect !";
            }
        }

        // 2. Si on demande la déconnexion
        if (isset($_GET['logout'])) {
            session_destroy();
            header("Location: index.php");
            exit();
        }

        // 3. VÉRIFICATION : Si pas connecté, on affiche le formulaire de login
        if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
            $this->renderLogin($error ?? null);
            return;
        }

        // 4. SI CONNECTÉ : On affiche les messages normalement
        $database = new Database();
        $db = $database->getConnection();
        $model = new Message($db);
        $messages = $model->findAll();

        require_once __DIR__ . '/../../views/admin.php';
    }

    private function renderLogin($error) {
        ?>
        <div style="text-align:center; margin-top:100px; font-family:sans-serif;">
            <h2>🔐 Accès Restreint</h2>
            <?php if($error): ?> <p style="color:red;"><?= $error ?></p> <?php endif; ?>
            <form method="POST">
                <input type="password" name="admin_password" placeholder="Mot de passe" required 
                       style="padding:10px; border-radius:5px; border:1px solid #ccc;">
                <button type="submit" style="padding:10px 20px; background:#007bff; color:white; border:none; border-radius:5px; cursor:pointer;">
                    Connexion
                </button>
            </form>
            <br><a href="index.php">Retour au site</a>
        </div>
        <?php
    }
}
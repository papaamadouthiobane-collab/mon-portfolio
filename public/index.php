<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$action = isset($_GET['action']) ? $_GET['action'] : 'accueil';
$root = dirname(__DIR__); 

// --- 1. ACTIONS DE TYPE "CONTROLLER" (Action directe) ---

if ($action === 'contact') {
    $controllerPath = $root . '/app/Controllers/ContactController.php';
    if (file_exists($controllerPath)) {
        require_once $controllerPath;
        $controller = new ContactController();
        $controller->submitForm();
    }
    exit();
} 

elseif ($action === 'admin') {
    $adminPath = $root . '/app/Controllers/AdminController.php';
    if (file_exists($adminPath)) {
        require_once $adminPath;
        $controller = new AdminController();
        $controller->index();
    }
    exit();
} 

// --- 2. ACTIONS DE TYPE "VUES" (Affichage des pages) ---

// Page de sélection des thèmes
elseif ($action === 'quiz') {
    if (file_exists('../views/quiz/selection.php')) {
        require_once '../views/quiz/selection.php';
    } else {
        die("Erreur : views/quiz/selection.php introuvable.");
    }
    exit();
} 

// PAGE DE JEU (C'est ici qu'on arrive au clic !)
elseif ($action === 'play') { 
    $theme = $_GET['theme'] ?? 'General';
    if (file_exists('../views/quiz/play.php')) {
        require_once '../views/quiz/play.php';
    } else {
        die("Erreur : views/quiz/play.php introuvable.");
    }
    exit();
}

// --- 3. PAGE D'ACCUEIL PAR DÉFAUT ---
else {
    ob_start();
    if (file_exists('../views/home.php')) {
        require_once '../views/home.php';
    }
    $content = ob_get_clean();

    if (file_exists('../views/layout.php')) {
        require_once '../views/layout.php';
    }
}
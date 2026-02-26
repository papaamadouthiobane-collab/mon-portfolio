<?php
// On capture le contenu pour l'injecter dans le layout
ob_start(); 
?>

<div class="hero">
    <h1>Salut ! Je suis Développeur</h1>
    <p>Bienvenue sur mon portfolio construit en architecture MVC.</p>
    <a href="#" class="btn">Voir mes outils</a>
</div>

<?php 
$content = ob_get_clean(); 
require_once '../views/layout.php';
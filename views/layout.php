<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfolio | Ahmad Dev</title>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
</head>
<body>
    <header class="main-header" style="position: fixed; top: 0; width: 100%; z-index: 2000; background: white;">
        <nav class="container nav-wrapper" style="position: relative;">
            <div class="logo">
                <img src="img/logo.png" alt="Logo Ahmad Dev" class="logo-img">
                <span class="logo-text">Ahmad <span style="color:#00b894">Dev</span></span>
            </div>

            <div class="nav-controls">
                <button id="lang-toggle" title="Changer de langue">
                    <i data-lucide="globe"></i>
                </button>
                <button id="theme-toggle" title="Mode sombre/clair">
                    <i data-lucide="moon" id="theme-icon"></i>
                </button>
            </div>

            <div class="menu-hamburger" onclick="toggleMenu()">
                <i data-lucide="menu" id="menu-icon"></i>
            </div>

            <ul class="nav-links" id="nav-links">
                <li><a href="#home" onclick="toggleMenu()">Accueil</a></li>
                <li><a href="#about" onclick="toggleMenu()">À Propos</a></li>
                <li><a href="#skills" onclick="toggleMenu()">Compétences</a></li>
                <li><a href="#contact" onclick="toggleMenu()">Contact</a></li>
            </ul>
        </nav>
    </header>

    <main><?php echo $content; ?></main>

    <script src="js/script.js"></script>
    <script>lucide.createIcons();</script>
</body>
</html>
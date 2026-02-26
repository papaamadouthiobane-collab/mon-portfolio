<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon Portfolio</title>
    <link rel="stylesheet" href="/mon-portfolio/public/css/style.css">
</head>
<body>
    <header>
        <nav>
            <div class="logo">DevPRO</div>
            <ul>
                <li><a href="/mon-portfolio/public/index.php">Accueil</a></li>
                <li><a href="#">Parcours</a></li>
                <li><a href="#">Outils</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <?php echo $content ?? "Bienvenue sur mon site !"; ?>
    </main>

    <footer style="text-align:center; padding: 20px;">
        &copy; <?php echo date('Y'); ?> - Portfolio Professionnel
    </footer>
</body>
</html>
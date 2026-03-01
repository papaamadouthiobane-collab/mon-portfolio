<!DOCTYPE html>
<html lang="fr" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sélection du Mode de Jeu</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        /* --- CONFIGURATION DU MODE FLUIDE --- */
        :root {
            --glass: rgba(255, 255, 255, 0.05);
            --border: rgba(255, 255, 255, 0.1);
            --primary: #3b82f6;
            --bg-color: #1a1d21;
            --text-color: #ffffff;
            --card-bg: rgba(255, 255, 255, 0.03);
        }

        [data-theme="light"] {
            --bg-color: #f1f5f9;
            --text-color: #1e293b;
            --border: rgba(0, 0, 0, 0.1);
            --card-bg: #ffffff;
            --glass: rgba(255, 255, 255, 0.8);
        }

        body {
            background-color: var(--bg-color) !important;
            color: var(--text-color);
            font-family: 'Inter', sans-serif;
            transition: background-color 0.5s ease, color 0.5s ease; /* Fluidité totale */
        }

        /* --- BARRE DE NAVIGATION --- */
        .nav-container {
            position: fixed;
            top: 20px;
            left: 20px;
            right: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 9999;
        }

        .btn-retour {
            text-decoration: none;
            color: var(--text-color);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: 0.3s;
            padding: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-retour:hover {
            color: var(--primary);
        }

        /* Animation de la flèche au survol */
        .btn-retour:hover .fleche {
            transform: translateX(-5px);
        }

        .fleche {
            transition: transform 0.3s ease;
            display: inline-block;
        }

        .theme-toggle-btn {
            background: var(--card-bg);
            border: 1px solid var(--border);
            color: var(--text-color);
            width: 45px;
            height: 45px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: 0.3s;
        }

        /* --- TES STYLES DE CARTES (ADAPTÉS) --- */
        .text-gradient {
            background: linear-gradient(to right, #60a5fa, #a855f7);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .card-glass {
            background: var(--glass);
            border: 1px solid var(--border);
            backdrop-filter: blur(10px);
            border-radius: 20px;
        }

        .theme-card {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 20px;
            transition: all 0.3s ease;
            cursor: pointer;
            height: 100%;
            color: var(--text-color);
        }

        .theme-card:hover {
            border-color: var(--primary);
            transform: translateY(-5px);
            background: rgba(59, 130, 246, 0.1);
        }

        .theme-icon {
            width: 50px; height: 50px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center; margin-bottom: 15px;
        }

        .icon-blue { background: rgba(59, 130, 246, 0.2); color: #3b82f6; }
        .icon-purple { background: rgba(168, 85, 247, 0.2); color: #a855f7; }
        .icon-orange { background: rgba(249, 115, 22, 0.2); color: #f97316; }
        .icon-green { background: rgba(16, 185, 129, 0.2); color: #10b981; }
        .icon-red { background: rgba(239, 68, 68, 0.2); color: #ef4444; }
        .icon-cyan { background: rgba(6, 182, 212, 0.2); color: #06b6d4; }
        .icon-pink { background: rgba(236, 72, 153, 0.2); color: #ec4899; }
        .icon-yellow { background: rgba(234, 179, 8, 0.2); color: #eab308; }

        .btn-genie, .btn-challenge {
            border: none; padding: 12px; border-radius: 12px;
            font-weight: 700; transition: 0.3s; color: white;
        }
        .btn-genie { background: linear-gradient(135deg, #f59e0b, #d97706); }
        .btn-challenge { background: linear-gradient(135deg, #3b82f6, #1d4ed8); }

        .play-label {
            font-size: 0.85rem; font-weight: 900; color: #60a5fa;
            text-transform: uppercase; display: flex; align-items: center; gap: 8px;
            animation: neon-blink 1.5s step-end infinite;
        }

        @keyframes neon-blink {
            0%, 100% { opacity: 1; } 50% { opacity: 0.5; }
        }
    </style>
</head>
<body> 

<div class="nav-container">
    <a href="index.php" class="btn-retour">
        <span class="fleche">←</span> RETOUR ACCUEIL
    </a>
    <div class="theme-toggle-btn" onclick="toggleTheme()">
        <i data-lucide="sun" id="theme-icon"></i>
    </div>
</div>

<div class="container py-5 mt-4">
    <div class="text-center mb-5">
        <h1 class="text-gradient display-4 fw-bold">Sélection du Mode de Jeu</h1>
        <p class="opacity-75 fs-5">Choisissez votre spécialité et relevez le défi.</p>
    </div>

    <div class="row g-4">
        <div class="col-lg-3">
            <div class="card-glass p-4 sticky-top shadow-lg" style="top: 80px;">
                <h5 class="mb-4 d-flex align-items-center justify-content-between">
                    <span><i data-lucide="cpu" class="me-2 text-primary"></i>SYSTEME</span>
                    <span class="badge bg-success">ONLINE</span>
                </h5>
                
                <div class="mb-4">
                    <label class="small text-uppercase fw-bold opacity-50 mb-2">Difficulté</label>
                    <select class="form-select bg-transparent text-inherit shadow-none" style="color: inherit;">
                        <option>Novice</option>
                        <option selected>Intermédiaire</option>
                        <option>Expert</option>
                    </select>
                </div>

                <button class="btn-genie w-100 mb-3 py-3" onclick="window.location.href='index.php?action=play&theme=Génie+en+Herbe'">
                    <i data-lucide="crown" class="me-2"></i>Génie en Herbe
                </button>

                <button class="btn-challenge w-100 py-3" onclick="window.location.href='index.php?action=play&theme=Challenge+100+Q'">
                    <i data-lucide="flame" class="me-2"></i>Challenge 100 Q
                </button>
            </div>
        </div>

        <div class="col-lg-9">
            <div class="row g-3">
                <?php 
                $themes = [
                    ['name' => 'Informatique', 'icon' => 'monitor', 'color' => 'blue'],
                    ['name' => 'Mathématiques', 'icon' => 'divide', 'color' => 'purple'],
                    ['name' => 'Physique-Chimie', 'icon' => 'flask-conical', 'color' => 'orange'],
                    ['name' => 'Biologie', 'icon' => 'dna', 'color' => 'green'],
                    ['name' => 'Médecine', 'icon' => 'stethoscope', 'color' => 'red'],
                    ['name' => 'Data Science', 'icon' => 'database', 'color' => 'cyan'],
                    ['name' => 'Développement', 'icon' => 'code-2', 'color' => 'pink'],
                    ['name' => 'Histoire', 'icon' => 'landmark', 'color' => 'yellow'],
                ];
                foreach($themes as $t): ?>
                <div class="col-md-4 col-sm-6">
                    <div class="theme-card" onclick="window.location.href='index.php?action=play&theme=<?= urlencode($t['name']) ?>'">
                        <div class="theme-icon icon-<?= $t['color'] ?>">
                            <i data-lucide="<?= $t['icon'] ?>"></i>
                        </div>
                        <h6 class="mb-1 fw-bold"><?= htmlspecialchars($t['name']) ?></h6>
                        <span class="play-label">JOUER <i data-lucide="circle-play" style="width:14px"></i></span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<script>
    lucide.createIcons();

    function toggleTheme() {
        const html = document.documentElement;
        const icon = document.getElementById('theme-icon');
        const isDark = html.getAttribute('data-theme') === 'dark';
        
        const newTheme = isDark ? 'light' : 'dark';
        html.setAttribute('data-theme', newTheme);
        
        // Change l'icône
        icon.setAttribute('data-lucide', isDark ? 'moon' : 'sun');
        lucide.createIcons();
    }
</script>

</body>
</html>
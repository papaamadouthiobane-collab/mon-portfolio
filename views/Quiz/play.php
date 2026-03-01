<?php
// 1. Récupération du thème et du mode
$theme_name = isset($_GET['theme']) ? $_GET['theme'] : 'Génie en Herbe';
$mode = isset($_GET['mode']) ? $_GET['mode'] : 'normal';
$theme_display = htmlspecialchars($theme_name);

// 2. Configuration Visuelle Dynamique
$is_challenge = ($theme_name === 'Challenge 100 Q');
$theme_color = $is_challenge ? '#00f2ff' : '#fbbf24'; 
$theme_glow = $is_challenge ? 'rgba(0, 242, 255, 0.15)' : 'rgba(251, 191, 36, 0.15)';
?>
<!DOCTYPE html>
<html lang="fr" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Elite - <?= $theme_display ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&family=JetBrains+Mono:wght@700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        :root {
            --bg: #030303;
            --card: #0a0a0c;
            --text: #ffffff;
            --mode-color: <?= $theme_color ?>;
            --mode-glow: <?= $theme_glow ?>;
            --success: #10b981;
            --error: #f43f5e;
            --muted: #64748b;
            --border: rgba(255, 255, 255, 0.05);
            --ease-out: cubic-bezier(0.16, 1, 0.3, 1);
        }

        [data-theme="light"] {
            --bg: #f8fafc;
            --card: #ffffff;
            --text: #1e293b;
            --border: rgba(0, 0, 0, 0.1);
            --muted: #94a3b8;
        }

        body { 
            background: var(--bg); font-family: 'Plus Jakarta Sans', sans-serif; 
            color: var(--text); min-height: 100vh; display: flex; align-items: center; justify-content: center; margin: 0;
            transition: background 0.4s ease, color 0.4s ease;
        }

        /* --- NAVIGATION --- */
        .nav-container {
            position: fixed; top: 20px; left: 20px; right: 20px;
            display: flex; justify-content: space-between; align-items: center; z-index: 9999;
        }

        .btn-retour {
            text-decoration: none; color: var(--text); font-weight: 800; font-size: 0.8rem;
            text-transform: uppercase; letter-spacing: 2px; display: flex; align-items: center; gap: 8px;
            cursor: pointer; transition: 0.3s;
        }
        .btn-retour:hover { color: var(--mode-color); }
        .btn-retour:hover .fleche { transform: translateX(-5px); }
        .fleche { transition: 0.3s var(--ease-out); display: inline-block; }

        .theme-toggle-btn {
            background: var(--card); border: 1px solid var(--border); color: var(--text);
            width: 42px; height: 42px; border-radius: 10px; display: flex;
            align-items: center; justify-content: center; cursor: pointer; transition: 0.3s;
        }

        /* --- STYLES DU QUIZ --- */
        .glass-stage { 
            background: var(--card); border: 1px solid var(--border); 
            border-top: 4px solid var(--mode-color); border-radius: 32px; 
            width: 95%; max-width: 750px; padding: 45px; position: relative; 
            box-shadow: 0 40px 100px rgba(0,0,0,0.15); animation: stageAppear 0.8s var(--ease-out);
        }

        @keyframes stageAppear { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

        .timer-racing {
            background: #000; border: 2px solid var(--mode-color); padding: 8px 20px; border-radius: 12px;
            color: var(--mode-color); font-family: 'JetBrains Mono', monospace; font-size: 1.4rem;
            transform: skewX(-10deg); box-shadow: 0 0 15px var(--mode-glow);
        }

        .progress-track { position: absolute; top: 0; left: 0; width: 100%; height: 4px; background: rgba(0,0,0,0.05); }
        .progress-bar { height: 100%; width: 0%; background: var(--mode-color); box-shadow: 0 0 15px var(--mode-color); transition: width 0.5s var(--ease-out); }
        
        .answer-card { 
            background: rgba(var(--muted-rgb), 0.05); border: 1px solid var(--border);
            padding: 20px; border-radius: 18px; margin-bottom: 12px; cursor: pointer;
            display: flex; align-items: center; transition: all 0.2s var(--ease-out); opacity: 0; color: var(--text);
        }
        .game-ready .answer-card { animation: cardAppear 0.4s var(--ease-out) forwards; }
        @keyframes cardAppear { from { opacity: 0; transform: translateX(-10px); } to { opacity: 1; transform: translateX(0); } }

        .answer-card:hover { background: var(--mode-glow); transform: scale(1.02) translateX(5px); }
        .answer-card.correct { border-color: var(--success) !important; color: var(--success); background: rgba(16,185,129,0.1) !important; }
        .answer-card.wrong { border-color: var(--error) !important; color: var(--error); background: rgba(244,63,94,0.1) !important; }
        
        .ans-letter { background: var(--border); color: var(--muted); font-weight: 800; font-size: 0.7rem; padding: 4px 8px; border-radius: 6px; margin-right: 15px; }

        .btn-main { background: var(--text); color: var(--bg); border: none; padding: 18px; border-radius: 50px; font-weight: 800; width: 100%; transition: 0.3s; margin-top: 20px; }
        .btn-main:hover { transform: translateY(-3px); filter: brightness(1.2); }

        .option-pill { padding: 10px 20px; border: 1px solid var(--border); border-radius: 50px; cursor: pointer; color: var(--muted); transition: 0.3s; }
        .option-pill.active { background: var(--mode-color); color: #000; font-weight: 700; border-color: var(--mode-color); }

        .hidden { display: none !important; }
        #combo-ui { position: absolute; bottom: 20px; right: 40px; text-align: right; }
        .combo-value { font-size: 2.2rem; font-weight: 900; color: var(--mode-color); line-height: 1; }
    </style>
</head>
<body>

<div class="nav-container">
    <a onclick="history.back()" class="btn-retour">
        <span class="fleche">←</span> RETOUR
    </a>
    <div class="theme-toggle-btn" onclick="toggleTheme()">
        <i data-lucide="sun" id="theme-icon" style="width: 20px;"></i>
    </div>
</div>

<div class="glass-stage" id="main-stage">
    <div class="progress-track"><div class="progress-bar" id="p-bar"></div></div>

    <div id="setup-ui" class="text-center">
        <span style="color:var(--mode-color); font-weight:800; font-size:0.7rem; text-transform:uppercase; letter-spacing:2px;">
            <?= $is_challenge ? 'Endurance Ultime' : 'Matrice de test' ?>
        </span>
        <h1 style="font-size: 3.5rem; font-weight: 800; margin-bottom: 40px;"><?= $theme_display ?></h1>
        
        <?php if(!$is_challenge): ?>
        <div class="d-flex gap-2 justify-content-center mb-5">
            <div class="option-pill" onclick="setQty(5, this)">5 Q</div>
            <div class="option-pill active" onclick="setQty(10, this)">10 Q</div>
            <div class="option-pill" onclick="setQty(20, this)">20 Q</div>
        </div>
        <?php else: ?>
            <p class="mb-5 opacity-75">Préparez-vous : 100 questions sans interruption.</p>
        <?php endif; ?>

        <button class="btn-main" onclick="startQuiz()">LANCER LE PROTOCOLE</button>
    </div>

    <div id="game-ui" class="hidden">
        <div class="d-flex justify-content-between align-items-start mb-5">
            <div>
                <span id="q-label" style="font-size: 0.75rem; font-weight: 800; color: var(--muted); letter-spacing: 1px;">SEQUENCE 01/10</span>
                <h2 id="q-text" style="margin-top:10px; font-weight: 700; min-height: 80px;">...</h2>
            </div>
            <div class="timer-racing" id="seconds">30</div>
        </div>
        <div id="ans-list"></div>
        <div id="combo-ui">
            <div class="combo-text small text-uppercase opacity-50">Combo</div>
            <div class="combo-value" id="combo-val">x0</div>
        </div>
    </div>

    <div id="result-ui" class="hidden text-center">
        <h1 id="final-score" style="font-size: 6rem; font-weight: 800; margin-bottom: 0;">0/0</h1>
        <p id="stat-accuracy" style="color: var(--muted); font-size: 1.2rem; margin-bottom: 40px;">Précision : 0%</p>
        <div class="d-flex gap-3">
            <button class="btn-main" onclick="location.reload()">REJOUER</button>
            <button class="btn-main" style="background:transparent; color:var(--text); border:1px solid var(--border);" onclick="history.back()">QUITTER</button>
        </div>
    </div>
</div>

<script>
    lucide.createIcons();

    function toggleTheme() {
        const html = document.documentElement;
        const icon = document.getElementById('theme-icon');
        const isDark = html.getAttribute('data-theme') === 'dark';
        html.setAttribute('data-theme', isDark ? 'light' : 'dark');
        icon.setAttribute('data-lucide', isDark ? 'moon' : 'sun');
        lucide.createIcons();
    }

    const quizData = {
        'Génie en Herbe': [
{ q: "Quel est l'oiseau qui ne vole pas mais court très vite ?", a: ["Aigle", "Autruche", "Colibri", "Perroquet"], c: 1 },
{ q: "Quelle est la capitale du Sénégal ?", a: ["Dakar", "Abidjan", "Bamako", "Lomé"], c: 0 },
{ q: "Combien de jours y a-t-il dans une année bissextile ?", a: ["364", "365", "366", "367"], c: 2 },
{ q: "Quel est le plus grand océan du monde ?", a: ["Atlantique", "Indien", "Pacifique", "Arctique"], c: 2 },
{ q: "Quel animal est appelé le roi de la savane ?", a: ["Tigre", "Lion", "Éléphant", "Guépard"], c: 1 },
{ q: "Quelle est la langue officielle du Brésil ?", a: ["Espagnol", "Français", "Portugais", "Anglais"], c: 2 },
{ q: "Quel gaz respirons-nous principalement ?", a: ["Oxygène", "Hydrogène", "Azote", "CO2"], c: 0 },
{ q: "Quel instrument mesure la température ?", a: ["Baromètre", "Thermomètre", "Voltmètre", "Balance"], c: 1 },
{ q: "Quel pays a construit les pyramides ?", a: ["Grèce", "Égypte", "Rome", "Chine"], c: 1 },
{ q: "Combien de continents existe-t-il ?", a: ["5", "6", "7", "8"], c: 2 },
{ q: "Quel est l’animal le plus rapide au monde ?", a: ["Guépard", "Lion", "Antilope", "Chien"], c: 0 },
{ q: "Quelle est la capitale du Mali ?", a: ["Dakar", "Bamako", "Conakry", "Niamey"], c: 1 },
{ q: "Quel métal est liquide à température ambiante ?", a: ["Fer", "Or", "Mercure", "Cuivre"], c: 2 },
{ q: "Combien font 9 x 9 ?", a: ["72", "81", "99", "90"], c: 1 },
{ q: "Quelle est la plus grande planète ?", a: ["Mars", "Jupiter", "Saturne", "Terre"], c: 1 },
{ q: "Qui a peint la Joconde ?", a: ["Van Gogh", "Picasso", "Léonard de Vinci", "Monet"], c: 2 },
{ q: "Quel est le fleuve le plus long du monde ?", a: ["Nil", "Congo", "Amazonie", "Sénégal"], c: 0 },
{ q: "Quel pays est surnommé le pays du Soleil-Levant ?", a: ["Chine", "Japon", "Corée", "Thaïlande"], c: 1 },
{ q: "Combien y a-t-il de minutes dans une heure ?", a: ["50", "60", "70", "80"], c: 1 },
{ q: "Quel est le symbole chimique de l'eau ?", a: ["H2O", "CO2", "O2", "NaCl"], c: 0 }
],

'Informatique': [
{ q: "Que signifie RAM ?", a: ["Random Access Memory", "Read Access Memory", "Real Advanced Memory"], c: 0 },
{ q: "Quel est l'ancêtre d'Internet ?", a: ["Google", "Arpanet", "Yahoo", "NASA"], c: 1 },
{ q: "Que signifie CPU ?", a: ["Central Process Unit", "Central Processing Unit", "Computer Personal Unit"], c: 1 },
{ q: "Quel est le cerveau de l’ordinateur ?", a: ["RAM", "Disque Dur", "Processeur", "Clavier"], c: 2 },
{ q: "Quel langage est utilisé pour structurer les pages web ?", a: ["HTML", "Python", "C++", "Java"], c: 0 },
{ q: "Que signifie HTTP ?", a: ["HyperText Transfer Protocol", "High Transfer Text Protocol", "Hyper Tool Transfer Program"], c: 0 },
{ q: "Quel est un système d’exploitation ?", a: ["Windows", "Word", "Chrome", "Excel"], c: 0 },
{ q: "Quel périphérique sert à afficher les informations ?", a: ["Clavier", "Souris", "Écran", "Scanner"], c: 2 },
{ q: "Quel stockage est permanent ?", a: ["RAM", "Cache", "ROM", "Registre"], c: 2 },
{ q: "Quel appareil permet d’imprimer ?", a: ["Scanner", "Imprimante", "Modem", "Routeur"], c: 1 },
{ q: "Que signifie USB ?", a: ["Universal Serial Bus", "United System Base", "Ultra System Boot"], c: 0 },
{ q: "Quel est un navigateur web ?", a: ["Firefox", "Linux", "Photoshop", "MySQL"], c: 0 },
{ q: "Quel langage est orienté objet ?", a: ["Java", "HTML", "CSS", "SQL"], c: 0 },
{ q: "Que signifie IP ?", a: ["Internet Protocol", "Internal Program", "Input Process"], c: 0 },
{ q: "Quel composant stocke les fichiers ?", a: ["Processeur", "Carte mère", "Disque dur", "Ventilateur"], c: 2 },
{ q: "Quel est un moteur de recherche ?", a: ["Google", "Windows", "Python", "USB"], c: 0 },
{ q: "Quel langage est utilisé pour les bases de données ?", a: ["SQL", "HTML", "CSS", "C"], c: 0 },
{ q: "Quel est un réseau social ?", a: ["Facebook", "Excel", "Word", "PowerPoint"], c: 0 },
{ q: "Quel appareil connecte un réseau à Internet ?", a: ["Routeur", "Clavier", "Écran", "Scanner"], c: 0 },
{ q: "Que signifie AI en anglais ?", a: ["Artificial Intelligence", "Automatic Input", "Advanced Internet"], c: 0 }
],
'Mathématiques': [
{ q: "Quelle est la valeur de la racine carrée de 64 ?", a: ["6", "7", "8", "9"], c: 2 },
{ q: "Comment appelle-t-on un polygone à 5 côtés ?", a: ["Hexagone", "Pentagone", "Heptagone"], c: 1 },
{ q: "Combien font 12 x 8 ?", a: ["96", "88", "108", "86"], c: 0 },
{ q: "Quel est le résultat de 45 ÷ 5 ?", a: ["8", "9", "7", "6"], c: 1 },
{ q: "Quelle est la formule de l’aire du rectangle ?", a: ["L x l", "πr²", "2πr", "L+l"], c: 0 },
{ q: "Combien de degrés dans un angle droit ?", a: ["45°", "90°", "180°", "360°"], c: 1 },
{ q: "Quelle est la valeur de π approximativement ?", a: ["2,14", "3,14", "4,13", "3,41"], c: 1 },
{ q: "Combien font 7² ?", a: ["14", "21", "49", "28"], c: 2 },
{ q: "Quel est le triple de 15 ?", a: ["30", "45", "60", "35"], c: 1 },
{ q: "Résoudre : 5 + 3 x 2 ?", a: ["16", "11", "13", "10"], c: 1 },
{ q: "Quel est le périmètre d’un carré de côté 4 ?", a: ["8", "12", "16", "20"], c: 2 },
{ q: "Quelle est la fraction équivalente à 1/2 ?", a: ["2/4", "3/4", "1/3", "4/5"], c: 0 },
{ q: "Combien font 100 - 37 ?", a: ["63", "73", "53", "67"], c: 0 },
{ q: "Quel est le carré de 9 ?", a: ["18", "81", "27", "90"], c: 1 },
{ q: "Combien de côtés a un triangle ?", a: ["2", "3", "4", "5"], c: 1 },
{ q: "Quelle est la moyenne de 10 et 20 ?", a: ["15", "20", "10", "12"], c: 0 },
{ q: "Combien font 6 x 7 ?", a: ["36", "42", "48", "40"], c: 1 },
{ q: "Quel est le résultat de 81 ÷ 9 ?", a: ["8", "9", "7", "6"], c: 1 },
{ q: "Quelle est la somme des angles d’un triangle ?", a: ["90°", "180°", "360°", "270°"], c: 1 },
{ q: "Combien font 25% de 200 ?", a: ["25", "50", "75", "100"], c: 1 }
],

'Physique-Chimie': [
{ q: "Quel est le symbole chimique de l'Or ?", a: ["Ag", "Fe", "Au", "Pb"], c: 2 },
{ q: "Quelle planète est surnommée la Planète Rouge ?", a: ["Vénus", "Mars", "Jupiter", "Saturne"], c: 1 },
{ q: "Quel est le symbole de l’oxygène ?", a: ["O", "Ox", "Og", "Om"], c: 0 },
{ q: "L’eau bout à combien de degrés Celsius ?", a: ["90°", "95°", "100°", "120°"], c: 2 },
{ q: "Quel gaz est responsable de la combustion ?", a: ["CO2", "O2", "H2", "N2"], c: 1 },
{ q: "Quelle est l’unité de la force ?", a: ["Joule", "Pascal", "Newton", "Watt"], c: 2 },
{ q: "Quel est l’état solide de l’eau ?", a: ["Vapeur", "Glace", "Pluie", "Neige"], c: 1 },
{ q: "Quelle planète est la plus proche du Soleil ?", a: ["Mars", "Mercure", "Vénus", "Terre"], c: 1 },
{ q: "Quel est le symbole du sodium ?", a: ["Na", "So", "S", "Sn"], c: 0 },
{ q: "Quelle est l’unité de l’énergie ?", a: ["Newton", "Joule", "Volt", "Ampère"], c: 1 },
{ q: "Quel métal attire les aimants ?", a: ["Or", "Fer", "Aluminium", "Cuivre"], c: 1 },
{ q: "Quelle est la formule du dioxyde de carbone ?", a: ["CO2", "O2", "H2O", "NaCl"], c: 0 },
{ q: "Quelle est la vitesse de la lumière ?", a: ["300 000 km/s", "150 000 km/s", "30 000 km/s", "3 000 km/s"], c: 0 },
{ q: "Quel est l’état gazeux de l’eau ?", a: ["Glace", "Vapeur", "Neige", "Pluie"], c: 1 },
{ q: "Quel est le symbole du fer ?", a: ["Fe", "Fr", "F", "Fi"], c: 0 },
{ q: "Quelle est l’unité de la tension électrique ?", a: ["Volt", "Watt", "Ampère", "Ohm"], c: 0 },
{ q: "Quel est le centre de notre système solaire ?", a: ["Terre", "Lune", "Mars", "Soleil"], c: 3 },
{ q: "Quel est le symbole du chlore ?", a: ["Cl", "Ch", "C", "Cr"], c: 0 },
{ q: "Quelle est l’unité de l’intensité électrique ?", a: ["Volt", "Watt", "Ampère", "Ohm"], c: 2 },
{ q: "Quel est l’état liquide de l’eau ?", a: ["Glace", "Vapeur", "Pluie", "Neige"], c: 2 }
],
    'Biologie': [
{ q: "Quelle molécule transporte l'oxygène dans le sang ?", a: ["Insuline", "Hémoglobine", "Chlorophylle"], c: 1 },
{ q: "Quel est l'organe le plus grand du corps humain ?", a: ["Foie", "Cœur", "Peau", "Poumon"], c: 2 },
{ q: "Quelle cellule est responsable de l’immunité ?", a: ["Globule rouge", "Globule blanc", "Plaquette"], c: 1 },
{ q: "Quelle partie de la plante fait la photosynthèse ?", a: ["Racine", "Tige", "Feuille"], c: 2 },
{ q: "Combien de chromosomes possède l’être humain ?", a: ["46", "44", "48"], c: 0 },
{ q: "Quel organe pompe le sang ?", a: ["Poumon", "Cœur", "Foie"], c: 1 },
{ q: "Quelle vitamine obtient-on grâce au soleil ?", a: ["Vitamine A", "Vitamine C", "Vitamine D"], c: 2 },
{ q: "Quel est l’organe de la respiration ?", a: ["Rein", "Poumon", "Estomac"], c: 1 },
{ q: "Quel animal est un mammifère marin ?", a: ["Requin", "Dauphin", "Sardine"], c: 1 },
{ q: "Quel est le rôle des racines ?", a: ["Respirer", "Absorber l’eau", "Produire des graines"], c: 1 },
{ q: "Quelle partie du cerveau contrôle l’équilibre ?", a: ["Cervelet", "Cortex", "Tronc cérébral"], c: 0 },
{ q: "Quelle est l’unité de base du vivant ?", a: ["Atome", "Molécule", "Cellule"], c: 2 },
{ q: "Quel gaz les plantes absorbent-elles ?", a: ["O2", "CO2", "H2"], c: 1 },
{ q: "Quel organe filtre le sang ?", a: ["Rein", "Cœur", "Poumon"], c: 0 },
{ q: "Combien de dents a un adulte ?", a: ["28", "30", "32"], c: 2 },
{ q: "Quel est le plus grand mammifère ?", a: ["Éléphant", "Baleine bleue", "Girafe"], c: 1 },
{ q: "Quelle partie de l’œil permet de voir ?", a: ["Rétine", "Nez", "Langue"], c: 0 },
{ q: "Quel organe digère les aliments ?", a: ["Estomac", "Cœur", "Poumon"], c: 0 },
{ q: "Quel est le groupe sanguin universel donneur ?", a: ["O-", "AB+", "A+"], c: 0 },
{ q: "Quelle est la science qui étudie les plantes ?", a: ["Zoologie", "Botanique", "Géologie"], c: 1 }
],

'Médecine': [
{ q: "Combien d'os possède un corps humain adulte ?", a: ["186", "206", "226", "246"], c: 1 },
{ q: "Quel médecin est spécialiste du cœur ?", a: ["Neurologue", "Cardiologue", "Pédiatre"], c: 1 },
{ q: "Quel appareil mesure la tension artérielle ?", a: ["Thermomètre", "Tensiomètre", "Balance"], c: 1 },
{ q: "Quelle maladie est causée par un virus ?", a: ["Paludisme", "Grippe", "Diabète"], c: 1 },
{ q: "Quel vaccin protège contre la tuberculose ?", a: ["BCG", "Polio", "ROR"], c: 0 },
{ q: "Quel organe est touché par l’hépatite ?", a: ["Foie", "Cœur", "Rein"], c: 0 },
{ q: "Quel est le rôle des antibiotiques ?", a: ["Tuer les bactéries", "Tuer les virus", "Soigner les fractures"], c: 0 },
{ q: "Quel est le taux normal de température ?", a: ["37°C", "35°C", "39°C"], c: 0 },
{ q: "Quel spécialiste soigne les enfants ?", a: ["Dermatologue", "Pédiatre", "Ophtalmologue"], c: 1 },
{ q: "Quel organe filtre les toxines ?", a: ["Foie", "Poumon", "Rate"], c: 0 },
{ q: "Quelle maladie est liée au sucre ?", a: ["Asthme", "Diabète", "Cancer"], c: 1 },
{ q: "Quel médecin soigne les yeux ?", a: ["ORL", "Ophtalmologue", "Cardiologue"], c: 1 },
{ q: "Quel appareil permet de voir l’intérieur du corps ?", a: ["Scanner", "Balance", "Stéthoscope"], c: 0 },
{ q: "Quel est le rôle du stéthoscope ?", a: ["Écouter le cœur", "Mesurer la fièvre", "Prendre la tension"], c: 0 },
{ q: "Quel organe produit l’insuline ?", a: ["Foie", "Pancréas", "Rein"], c: 1 },
{ q: "Quelle carence cause l’anémie ?", a: ["Fer", "Calcium", "Magnésium"], c: 0 },
{ q: "Quel est le nom du médecin des os ?", a: ["Orthopédiste", "Cardiologue", "Neurologue"], c: 0 },
{ q: "Quel est le rôle des globules rouges ?", a: ["Immunité", "Transport oxygène", "Coagulation"], c: 1 },
{ q: "Quelle maladie touche les poumons ?", a: ["Asthme", "Ulcer", "Migraine"], c: 0 },
{ q: "Quel spécialiste soigne la peau ?", a: ["Dermatologue", "Dentiste", "ORL"], c: 0 }
],

'Data Science': [
{ q: "Quel langage est le plus utilisé en Data Science ?", a: ["Java", "Python", "PHP", "C++"], c: 1 },
{ q: "Qu'est-ce qu'un Outlier ?", a: ["Donnée moyenne", "Donnée aberrante", "Donnée manquante"], c: 1 },
{ q: "Quel outil est utilisé pour l’analyse de données ?", a: ["Excel", "Paint", "Word"], c: 0 },
{ q: "Que signifie IA ?", a: ["Intelligence Artificielle", "Information Avancée", "Internet Automatique"], c: 0 },
{ q: "Quel type de graphique montre une tendance ?", a: ["Courbe", "Tableau", "Texte"], c: 0 },
{ q: "Que signifie Big Data ?", a: ["Petites données", "Grand volume de données", "Données lentes"], c: 1 },
{ q: "Quel est un algorithme de classification ?", a: ["KNN", "HTML", "CSS"], c: 0 },
{ q: "Quel est un outil de visualisation ?", a: ["Tableau", "Bloc-notes", "Chrome"], c: 0 },
{ q: "Quel format est utilisé pour les données structurées ?", a: ["CSV", "MP3", "PNG"], c: 0 },
{ q: "Quel concept mesure la précision d’un modèle ?", a: ["Accuracy", "Volume", "Poids"], c: 0 },
{ q: "Quel langage est utilisé avec pandas ?", a: ["Python", "C", "PHP"], c: 0 },
{ q: "Quel est un type de base de données ?", a: ["SQL", "JPEG", "MP4"], c: 0 },
{ q: "Quel est un modèle supervisé ?", a: ["Régression", "Compression", "Copie"], c: 0 },
{ q: "Quel outil est utilisé pour notebook ?", a: ["Jupyter", "Excel", "Word"], c: 0 },
{ q: "Que signifie ML ?", a: ["Machine Learning", "Main Logic", "Mega Link"], c: 0 },
{ q: "Quel est un exemple de clustering ?", a: ["K-means", "HTML", "USB"], c: 0 },
{ q: "Quel est un langage statistique ?", a: ["R", "HTML", "CSS"], c: 0 },
{ q: "Quel indicateur mesure l’erreur ?", a: ["MSE", "CPU", "RAM"], c: 0 },
{ q: "Quel est un type de variable ?", a: ["Numérique", "USB", "Routeur"], c: 0 },
{ q: "Quel est un outil de dashboard ?", a: ["Power BI", "Paint", "Bloc-notes"], c: 0 }
],

'Développement': [
{ q: "Que signifie DOM ?", a: ["Document Object Model", "Data Object Management", "Digital Optimal Mode"], c: 0 },
{ q: "Lequel est un framework JavaScript ?", a: ["Laravel", "React", "Django", "Flask"], c: 1 },
{ q: "Quel langage structure une page web ?", a: ["HTML", "Python", "C++"], c: 0 },
{ q: "Quel langage stylise une page web ?", a: ["CSS", "SQL", "C"], c: 0 },
{ q: "Quel langage rend la page interactive ?", a: ["JavaScript", "HTML", "CSS"], c: 0 },
{ q: "Quel framework PHP est populaire ?", a: ["Laravel", "React", "Vue"], c: 0 },
{ q: "Quel est un framework Python ?", a: ["Django", "Laravel", "Spring"], c: 0 },
{ q: "Quel est un système de versionning ?", a: ["Git", "Excel", "Chrome"], c: 0 },
{ q: "Quel site héberge du code ?", a: ["GitHub", "Facebook", "Instagram"], c: 0 },
{ q: "Quel mot-clé déclare une variable en JS ?", a: ["let", "echo", "print"], c: 0 },
{ q: "Quel est un langage backend ?", a: ["PHP", "CSS", "HTML"], c: 0 },
{ q: "Quel est un framework frontend ?", a: ["Vue", "MySQL", "Oracle"], c: 0 },
{ q: "Quel protocole sécurise un site ?", a: ["HTTPS", "FTP", "SMTP"], c: 0 },
{ q: "Quel est un SGBD ?", a: ["MySQL", "React", "Bootstrap"], c: 0 },
{ q: "Quel outil teste une API ?", a: ["Postman", "Paint", "Bloc-notes"], c: 0 },
{ q: "Quel est un CMS ?", a: ["WordPress", "Python", "C++"], c: 0 },
{ q: "Quel est un langage mobile ?", a: ["Kotlin", "HTML", "CSS"], c: 0 },
{ q: "Quel est un moteur JS côté serveur ?", a: ["Node.js", "Apache", "IIS"], c: 0 },
{ q: "Quel est un design responsive ?", a: ["Bootstrap", "MySQL", "PHP"], c: 0 },
{ q: "Quel est un IDE ?", a: ["VS Code", "Chrome", "Windows"], c: 0 }
],

'Histoire': [
{ q: "En quelle année a débuté la Révolution Française ?", a: ["1779", "1789", "1799", "1809"], c: 1 },
{ q: "Qui était surnommé le Roi Soleil ?", a: ["Louis XIV", "Louis XVI", "Napoléon"], c: 0 },
{ q: "Qui a découvert l’Amérique en 1492 ?", a: ["Christophe Colomb", "Magellan", "Napoléon"], c: 0 },
{ q: "Qui était le premier président des USA ?", a: ["George Washington", "Lincoln", "Obama"], c: 0 },
{ q: "En quelle année le Sénégal a obtenu son indépendance ?", a: ["1958", "1960", "1970"], c: 1 },
{ q: "Qui était Napoléon ?", a: ["Empereur français", "Roi d’Espagne", "Président italien"], c: 0 },
{ q: "Quel mur est tombé en 1989 ?", a: ["Mur de Berlin", "Mur de Chine", "Mur d’Italie"], c: 0 },
{ q: "Qui était le premier homme sur la Lune ?", a: ["Neil Armstrong", "Buzz Aldrin", "Youri Gagarine"], c: 0 },
{ q: "Quelle guerre a duré de 1914 à 1918 ?", a: ["Première Guerre mondiale", "Seconde Guerre mondiale", "Guerre froide"], c: 0 },
{ q: "Qui était Cheikh Anta Diop ?", a: ["Historien", "Médecin", "Militaire"], c: 0 },
{ q: "Quel empire a construit les pyramides ?", a: ["Égyptien", "Romain", "Grec"], c: 0 },
{ q: "Qui était Hitler ?", a: ["Dirigeant allemand", "Roi anglais", "Président russe"], c: 0 },
{ q: "Quelle guerre a duré de 1939 à 1945 ?", a: ["Seconde Guerre mondiale", "Première Guerre mondiale", "Guerre froide"], c: 0 },
{ q: "Qui était Martin Luther King ?", a: ["Militant des droits civiques", "Président", "Général"], c: 0 },
{ q: "Quel pays a colonisé le Sénégal ?", a: ["France", "Espagne", "Portugal"], c: 0 },
{ q: "Qui était Nelson Mandela ?", a: ["Président sud-africain", "Roi", "Explorateur"], c: 0 },
{ q: "Quel événement a commencé en 1789 ?", a: ["Révolution française", "Guerre mondiale", "Indépendance USA"], c: 0 },
{ q: "Qui était Léopold Sédar Senghor ?", a: ["Président sénégalais", "Général", "Explorateur"], c: 0 },
{ q: "Quelle civilisation a construit le Colisée ?", a: ["Romaine", "Grecque", "Égyptienne"], c: 0 },
{ q: "Qui était Abraham Lincoln ?", a: ["Président américain", "Roi", "Empereur"], c: 0 }
],

'Challenge 100 Q': [
        { q: "Quelle est la capitale du Sénégal ?", a: ["Saint-Louis", "Dakar", "Thiès", "Ziguinchor"], c: 1 },
        { q: "Qui a peint la 'Mona Lisa' ?", a: ["Van Gogh", "Picasso", "Léonard de Vinci", "Claude Monet"], c: 2 },
        { q: "Quel est l'oiseau qui ne vole pas ?", a: ["Aigle", "Autruche", "Colibri", "Faucon"], c: 1 },
        { q: "Combien de continents ?", a: ["5", "6", "7", "8"], c: 2 },
        { q: "Monnaie du Japon ?", a: ["Yuan", "Won", "Yen", "Dollar"], c: 2 },
        { q: "Plus grand océan ?", a: ["Atlantique", "Indien", "Pacifique", "Arctique"], c: 2 },
        { q: "Pays des Pyramides ?", a: ["Soudan", "Égypte", "Mexique", "Grèce"], c: 1 },
        { q: "Plus grand mammifère terrestre ?", a: ["Girafe", "Rhinocéros", "Éléphant d'Afrique", "Hippopotame"], c: 2 },
        { q: "Langue la plus parlée (natifs) ?", a: ["Anglais", "Espagnol", "Mandarin", "Hindi"], c: 2 },
        { q: "Auteur de 'Germinal' ?", a: ["Victor Hugo", "Émile Zola", "Molière", "Balzac"], c: 1 },
        { q: "Gaz principal respiré ?", a: ["Oxygène", "Azote", "CO2"], c: 1 },
        { q: "Formule de l'eau ?", a: ["CO2", "H2O", "O2"], c: 1 },
        { q: "Planète rouge ?", a: ["Vénus", "Mars", "Jupiter"], c: 1 },
        { q: "Ébullition de l'eau ?", a: ["90°C", "100°C", "110°C"], c: 1 },
        { q: "Organe pompe sang ?", a: ["Poumon", "Cerveau", "Cœur"], c: 2 },
        { q: "Vitamine du soleil ?", a: ["Vitamine A", "Vitamine C", "Vitamine D"], c: 2 },
        { q: "Nombre d'os adulte ?", a: ["186", "206", "226"], c: 1 },
        { q: "Étoile la plus proche ?", a: ["Sirius", "Le Soleil", "Proxima"], c: 1 },
        { q: "Animal le plus rapide ?", a: ["Lion", "Guépard", "Antilope"], c: 1 },
        { q: "Science des fossiles ?", a: ["Géologie", "Paléontologie", "Archéologie"], c: 1 },
        { q: "12 x 12 ?", a: ["124", "134", "144"], c: 2 },
        { q: "Racine de 81 ?", a: ["7", "8", "9"], c: 2 },
        { q: "Polygone 5 côtés ?", a: ["Hexagone", "Pentagone", "Heptagone"], c: 1 },
        { q: "Valeur de Pi ?", a: ["3,12", "3,14", "3,16"], c: 1 },
        { q: "Angle droit ?", a: ["45°", "90°", "180°"], c: 1 },
        { q: "150 / 5 ?", a: ["25", "30", "35"], c: 1 },
        { q: "Carré de 7 ?", a: ["14", "47", "49"], c: 2 },
        { q: "Si x + 5 = 12, x = ?", a: ["5", "7", "8"], c: 1 },
        { q: "Secondes dans 1h ?", a: ["60", "600", "3600"], c: 2 },
        { q: "Périmètre carré côté 5 ?", a: ["10", "20", "25"], c: 1 },
        { q: "Signification RAM ?", a: ["Read", "Random", "Real"], c: 1 },
        { q: "Style Web ?", a: ["HTML", "PHP", "CSS"], c: 2 },
        { q: "Co-fondateur Microsoft ?", a: ["Jobs", "Gates", "Musk"], c: 1 },
        { q: "Cerveau ordi ?", a: ["DD", "GPU", "CPU"], c: 2 },
        { q: "HTTP ?", a: ["HyperText", "HighTech", "HyperTool"], c: 0 },
        { q: "OS de Google ?", a: ["iOS", "Windows", "Android"], c: 2 },
        { q: "Unité stockage ?", a: ["Hertz", "Volt", "Octet"], c: 2 },
        { q: "IA ?", a: ["Internet", "Intelligence", "Informatique"], c: 1 },
        { q: "Moteur recherche ?", a: ["Bing", "Yahoo", "Google"], c: 2 },
        { q: "URL ?", a: ["Réseau", "Adresse Web", "User"], c: 1 },
        { q: "Début 1ère Guerre ?", a: ["1912", "1914", "1939"], c: 1 },
        { q: "1er président Sénégal ?", a: ["Diouf", "Senghor", "Wade"], c: 1 },
        { q: "Don Statue Liberté ?", a: ["UK", "France", "Italie"], c: 1 },
        { q: "Nelson Mandela ?", a: ["Roi", "Président SA", "Explo"], c: 1 },
        { q: "Chute Mur Berlin ?", a: ["1985", "1989", "1991"], c: 1 },
        { q: "Christophe Colomb ?", a: ["1492", "1592", "1692"], c: 0 },
        { q: "Perdant Waterloo ?", a: ["Louis XIV", "Napoléon", "Henri IV"], c: 1 },
        { q: "Lune ?", a: ["1959", "1969", "1979"], c: 1 },
        { q: "Hiéroglyphes ?", a: ["Romaine", "Grecque", "Égyptienne"], c: 2 },
        { q: "Martin Luther King ?", a: ["Physicien", "Militant", "Roi"], c: 1 },
        { q: "Symbole Or ?", a: ["Ag", "Au", "Fe"], c: 1 },
        { q: "Gravité ?", a: ["Magnétisme", "Friction", "Gravité"], c: 2 },
        { q: "Unité Force ?", a: ["Joule", "Watt", "Newton"], c: 2 },
        { q: "Métal liquide ?", a: ["Plomb", "Mercure", "Cuivre"], c: 1 },
        { q: "Solide à liquide ?", a: ["Fusion", "Vapeur", "Glace"], c: 0 },
        { q: "Gaz abondant air ?", a: ["Oxygène", "Diazote", "CO2"], c: 1 },
        { q: "Relativité ?", a: ["Newton", "Einstein", "Curie"], c: 1 },
        { q: "pH neutre ?", a: ["0", "7", "14"], c: 1 },
        { q: "Atome composé de ?", a: ["Protons", "Photons", "Molécules"], c: 0 },
        { q: "Tension électrique ?", a: ["Ampère", "Volt", "Ohm"], c: 1 },
        { q: "Plus grand pays ?", a: ["Canada", "Chine", "Russie"], c: 2 },
        { q: "Fleuve Égypte ?", a: ["Congo", "Nil", "Amazone"], c: 1 },
        { q: "Capitale Mali ?", a: ["Dakar", "Bamako", "Niamey"], c: 1 },
        { q: "Continent Brésil ?", a: ["Afrique", "Europe", "Am. Sud"], c: 2 },
        { q: "Soleil Levant ?", a: ["Chine", "Corée", "Japon"], c: 2 },
        { q: "Capitale Allemagne ?", a: ["Munich", "Berlin", "Francfort"], c: 1 },
        { q: "Tour Eiffel ?", a: ["Lyon", "Londres", "Paris"], c: 2 },
        { q: "Montagne la plus haute ?", a: ["Mont Blanc", "Everest", "K2"], c: 1 },
        { q: "Désert le plus grand ?", a: ["Gobi", "Sahara", "Atacama"], c: 1 },
        { q: "Capitale Canada ?", a: ["Toronto", "Montréal", "Ottawa"], c: 2 },
        { q: "SQL ?", a: ["Simple", "Structured", "System"], c: 1 },
        { q: "ID en CSS ?", a: [".", "#", "$"], c: 1 },
        { q: "Framework JS ?", a: ["Laravel", "React", "Django"], c: 1 },
        { q: "Android natif ?", a: ["Swift", "Kotlin", "PHP"], c: 1 },
        { q: "JS ?", a: ["JustStyle", "JavaScript", "JavaSource"], c: 1 },
        { q: "Port HTTP ?", a: ["21", "80", "443"], c: 1 },
        { q: "Git ?", a: ["Données", "Versions", "Fichiers"], c: 1 },
        { q: "Constante JS ?", a: ["var", "let", "const"], c: 2 },
        { q: "CSS ?", a: ["Cascading", "Color", "Computer"], c: 0 },
        { q: "Variable PHP ?", a: ["#", "@", "$"], c: 2 },
        { q: "Donneur universel ?", a: ["A+", "B-", "O-"], c: 2 },
        { q: "Organe plus grand ?", a: ["Foie", "Poumons", "Peau"], c: 2 },
        { q: "Code génétique ?", a: ["ARN", "ADN", "ATP"], c: 1 },
        { q: "Transport oxygène ?", a: ["Globule blanc", "Globule rouge", "Neurone"], c: 1 },
        { q: "Médecin enfants ?", a: ["Pédiatre", "Cardio", "ORL"], c: 0 },
        { q: "Sucre du sang ?", a: ["Fructose", "Glucose", "Lactose"], c: 1 },
        { q: "Fémur ?", a: ["Bras", "Dos", "Cuisse"], c: 2 },
        { q: "Hépatite ?", a: ["Rein", "Cœur", "Foie"], c: 2 },
        { q: "Dents adulte ?", a: ["28", "32", "34"], c: 1 },
        { q: "Globules blancs ?", a: ["Oxygène", "Immunité", "Digestion"], c: 1 },
        { q: "Pommes ?", a: ["1", "2", "3"], c: 1 },
        { q: "Mois 28 jours ?", a: ["Février", "Janvier", "Tous"], c: 2 },
        { q: "Âge ?", a: ["Pluie", "Ton âge", "Ballon"], c: 1 },
        { q: "Marie ?", a: ["Lulu", "Marie", "Lala"], c: 1 },
        { q: "Capitale Italie ?", a: ["Venise", "Naples", "Rome"], c: 2 },
        { q: "0 x 1000 ?", a: ["1000", "100", "0"], c: 2 },
        { q: "Inverse Vrai ?", a: ["Peut-être", "Faux", "Nul"], c: 1 },
        { q: "Cri lion ?", a: ["Aboiement", "Rugissement", "Bêlement"], c: 1 },
        { q: "Sport populaire ?", a: ["Tennis", "Basket", "Football"], c: 2 },
        { q: "100ème Q ?", a: ["Celle-ci", "Une autre", "Fin"], c: 0 }

        ]
    };

    let currentQ = 0;
    let score = 0;
    let combo = 0;
    let timer;
    let timeLeft = 30;
    let maxQty = <?= $is_challenge ? 100 : 10 ?>;
    let activeQuestions = [];

    function setQty(n, el) {
        maxQty = n;
        document.querySelectorAll('.option-pill').forEach(p => p.classList.remove('active'));
        el.classList.add('active');
    }

    function startQuiz() {
        const pool = quizData['<?= $theme_name ?>'] || quizData['Génie en Herbe'];
        activeQuestions = pool.sort(() => 0.5 - Math.random()).slice(0, maxQty);
        document.getElementById('setup-ui').classList.add('hidden');
        document.getElementById('game-ui').classList.remove('hidden');
        showQuestion();
    }

    function showQuestion() {
        if(currentQ >= activeQuestions.length) return endQuiz();
        
        const q = activeQuestions[currentQ];
        document.getElementById('q-label').innerText = `SEQUENCE ${String(currentQ + 1).padStart(2, '0')}/${activeQuestions.length}`;
        document.getElementById('q-text').innerText = q.q;
        
        const list = document.getElementById('ans-list');
        list.innerHTML = '';
        document.getElementById('main-stage').classList.remove('game-ready');
        
        q.a.forEach((opt, i) => {
            const div = document.createElement('div');
            div.className = 'answer-card';
            div.style.animationDelay = (i * 0.1) + 's';
            div.innerHTML = `<span class="ans-letter">${String.fromCharCode(65 + i)}</span> ${opt}`;
            div.onclick = () => checkAns(i, div);
            list.appendChild(div);
        });

        setTimeout(() => document.getElementById('main-stage').classList.add('game-ready'), 10);
        startTimer();
        updateProgress();
    }

    function checkAns(idx, el) {
        clearInterval(timer);
        const correct = activeQuestions[currentQ].c;
        const cards = document.querySelectorAll('.answer-card');
        cards.forEach(c => c.style.pointerEvents = 'none');

        if(idx === correct) {
            el.classList.add('correct');
            score++;
            combo++;
        } else {
            el.classList.add('wrong');
            cards[correct].classList.add('correct');
            combo = 0;
        }

        document.getElementById('combo-val').innerText = 'x' + combo;
        currentQ++;
        setTimeout(showQuestion, 1200);
    }

    function startTimer() {
        timeLeft = 30;
        document.getElementById('seconds').innerText = timeLeft;
        clearInterval(timer);
        timer = setInterval(() => {
            timeLeft--;
            document.getElementById('seconds').innerText = timeLeft;
            if(timeLeft <= 0) checkAns(-1, null);
        }, 1000);
    }

    function updateProgress() {
        const p = (currentQ / activeQuestions.length) * 100;
        document.getElementById('p-bar').style.width = p + '%';
    }

    function endQuiz() {
        document.getElementById('game-ui').classList.add('hidden');
        document.getElementById('result-ui').classList.remove('hidden');
        document.getElementById('final-score').innerText = `${score}/${activeQuestions.length}`;
        document.getElementById('stat-accuracy').innerText = `Précision : ${Math.round((score/activeQuestions.length)*100)}%`;
    }
</script>
</body>
</html>
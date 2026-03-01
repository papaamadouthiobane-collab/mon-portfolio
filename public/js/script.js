/**
 * 1. GESTION DU MENU RIDEAU (3 TRAITS)
 */
function toggleMenu() {
    const nav = document.getElementById('nav-links');
    const icon = document.getElementById('menu-icon');
    
    // 1. Alterne la classe 'active' sur le menu
    nav.classList.toggle('active');

    // 2. Vérifie si le menu est ouvert pour changer l'icône
    if (nav.classList.contains('active')) {
        // Si ouvert -> montre la croix (X)
        icon.setAttribute('data-lucide', 'x');
    } else {
        // Si fermé -> montre les 3 traits (menu)
        icon.setAttribute('data-lucide', 'menu');
    }
    
    // 3. Demande à Lucide de rafraîchir l'icône visuellement
    lucide.createIcons();
}

// Optionnel : Fermer le menu automatiquement quand on clique sur un lien
document.querySelectorAll('#nav-links a').forEach(link => {
    link.addEventListener('click', () => {
        const nav = document.getElementById('nav-links');
        if (nav.classList.contains('active')) {
            toggleMenu();
        }
    });
});

/**
 * 2. CHANGEMENT DE LANGUE (FR/EN)
 */
const langBtn = document.getElementById('lang-toggle');
let currentLang = 'fr';

if (langBtn) {
    langBtn.addEventListener('click', () => {
        currentLang = currentLang === 'fr' ? 'en' : 'fr';
        
        // Exemple de bascule simple
        if (currentLang === 'en') {
            alert("Switching to English...");
            // Tu pourras ajouter ici ta logique de traduction (ex: redirection ou JSON)
        } else {
            alert("Passage en Français...");
        }
    });
}

/**
 * 3. MODE SOMBRE
 */
const themeToggle = document.getElementById('theme-toggle');
if (themeToggle) {
    themeToggle.addEventListener('click', () => {
        const html = document.documentElement;
        const isDark = html.getAttribute('data-theme') === 'dark';
        
        html.setAttribute('data-theme', isDark ? 'light' : 'dark');
        
        const themeIcon = document.getElementById('theme-icon');
        themeIcon.setAttribute('data-lucide', isDark ? 'moon' : 'sun');
        lucide.createIcons();
    });
}

/**
 * 4. INITIALISATION
 */
document.addEventListener('DOMContentLoaded', () => {
    lucide.createIcons();

    // Animation au scroll (Reveal)
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('active');
            }
        });
    }, { threshold: 0.1 });

    document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
});

// GESTION DU MENU HAMBURGER
function toggleMenu() {
    const nav = document.getElementById('nav-links');
    const icon = document.getElementById('menu-icon');
    
    nav.classList.toggle('active');

    // Change l'icône
    if (nav.classList.contains('active')) {
        icon.setAttribute('data-lucide', 'x');
    } else {
        icon.setAttribute('data-lucide', 'menu');
    }
    
    // SURTOUT PAS DE "body.style.overflow = hidden" ICI
    // On veut pouvoir scroller !
    
    lucide.createIcons();
}

// Fermer le menu si on clique sur un lien
document.querySelectorAll('.nav-links a').forEach(link => {
    link.addEventListener('click', () => {
        document.getElementById('nav-links').classList.remove('active');
        document.getElementById('menu-icon').setAttribute('data-lucide', 'menu');
        lucide.createIcons();
    });
});
// GESTION DU CHANGEMENT DE LANGUE
document.getElementById('lang-toggle').addEventListener('click', function() {
    const currentLang = document.documentElement.lang;
    if (currentLang === 'fr') {
        document.documentElement.lang = 'en';
        alert('Language switched to English');
        // Ici tu peux changer tes textes manuellement ou recharger la page
    } else {
        document.documentElement.lang = 'fr';
        alert('Langue changée en Français');
    }
});

document.addEventListener('DOMContentLoaded', () => {
    const textElement = document.getElementById('typewriter');

    const texts = [
        "Développeur Fullstack",
        "Étudiant en Informatique",
        "Passionné de Technologie",
        "Créateur de Solutions"
    ];

    let textIndex = 0;
    let charIndex = 0;
    let isDeleting = false;

    function typeEffect() {
        const currentText = texts[textIndex];

        if (!isDeleting) {
            textElement.textContent = currentText.substring(0, charIndex + 1);
            charIndex++;

            if (charIndex === currentText.length) {
                setTimeout(() => isDeleting = true, 1000);
            }
        } else {
            textElement.textContent = currentText.substring(0, charIndex - 1);
            charIndex--;

            if (charIndex === 0) {
                isDeleting = false;
                textIndex++;
                if (textIndex === texts.length) {
                    textIndex = 0;
                }
            }
        }

        setTimeout(typeEffect, 80);
    }

    typeEffect();
});

document.addEventListener('DOMContentLoaded', () => {
    // Gestion de l'accordéon
    const headers = document.querySelectorAll('.group-header');
    
    headers.forEach(header => {
        header.addEventListener('click', () => {
            const group = header.parentElement;
            
            // Ferme les autres groupes ouverts (optionnel)
            document.querySelectorAll('.skill-group').forEach(otherGroup => {
                if (otherGroup !== group) otherGroup.classList.remove('open');
            });

            // Ouvre ou ferme le groupe actuel
            group.classList.toggle('open');
        });
    });

    // Initialisation des icônes Lucide
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
});
document.querySelectorAll('.group-header').forEach(header => {
    header.addEventListener('click', () => {
        const group = header.parentElement;
        group.classList.toggle('active');
        
        // Optionnel : Fermer les autres quand on en ouvre un
        // document.querySelectorAll('.skill-group').forEach(other => {
        //    if (other !== group) other.classList.remove('active');
        // });
    });
});
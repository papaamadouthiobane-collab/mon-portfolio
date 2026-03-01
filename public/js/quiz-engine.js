const database = {
    "Médecine": [
        { q: "Quel est l'os le plus long du corps humain ?", a: ["Tibia", "Fémur", "Humérus", "Radius"], r: 1 },
        { q: "Quel organe produit l'insuline ?", a: ["Foie", "Pancréas", "Rate", "Rein"], r: 1 }
    ],
    "Informatique": [
        { q: "Que signifie SQL ?", a: ["Simple Query Language", "Structured Query Language", "System Quick Link", "Solid Quantified Logic"], r: 1 },
        { q: "Quel protocole est utilisé pour naviguer sur le web ?", a: ["FTP", "SMTP", "HTTP", "SSH"], r: 2 }
    ]
};

let questions = database[currentTheme] || database["Informatique"];
let currentIdx = 0;
let score = 0;
let timeLeft = 15;
let timer;

function initQuiz() {
    lucide.createIcons();
    showQuestion();
}

function showQuestion() {
    if (currentIdx >= questions.length) return finishGame();
    
    resetTimer();
    const q = questions[currentIdx];
    document.getElementById('question-text').innerText = q.q;
    document.getElementById('progress').style.width = ((currentIdx / questions.length) * 100) + "%";
    
    const grid = document.getElementById('answer-options');
    grid.innerHTML = "";
    
    q.a.forEach((opt, i) => {
        const btn = document.createElement('button');
        btn.className = "btn-answer";
        btn.innerText = opt;
        btn.onclick = () => checkAnswer(i);
        grid.appendChild(btn);
    });
}

function checkAnswer(idx) {
    if (idx === questions[currentIdx].r) {
        score += 100;
        document.getElementById('score-display').innerText = "SCORE: " + score;
    }
    currentIdx++;
    showQuestion();
}

function resetTimer() {
    clearInterval(timer);
    timeLeft = 15;
    const circle = document.querySelector('.timer-circle');
    circle.style.strokeDashoffset = 0;
    
    timer = setInterval(() => {
        timeLeft--;
        document.getElementById('timer-text').innerText = timeLeft;
        circle.style.strokeDashoffset = 235 - (235 * (15 - timeLeft) / 15);
        
        if (timeLeft <= 0) {
            currentIdx++;
            showQuestion();
        }
    }, 1000);
}

function finishGame() {
    clearInterval(timer);
    document.getElementById('quiz-content').innerHTML = `
        <h1 class="text-success mb-3">MISSION TERMINÉE</h1>
        <p class="fs-4">Votre score final : <span class="text-info">${score}</span></p>
        <button onclick="window.location.href='index.php?action=quiz'" class="btn btn-outline-primary mt-4">Retour au menu</button>
    `;
}

initQuiz();


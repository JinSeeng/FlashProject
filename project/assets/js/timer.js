// Variables du timer
let startTime;
let timerInterval;
let elapsedTime = 0;
const timerElement = document.getElementById('time');

// Fonction pour le format en MM/SS
function formatTime(seconds) {
    const minutes = Math.floor(seconds / 60);
    const remainingSeconds = seconds % 60;
    return `${minutes.toString().padStart(2, '0')}:${remainingSeconds.toString().padStart(2, '0')}`;
}

// Fonction pour lancer le timer
function startTimer() {
    startTime = Date.now();
    timerInterval = setInterval(() => {
        elapsedTime = Math.floor((Date.now() - startTime) / 1000);
        timerElement.textContent = formatTime(elapsedTime);
    }, 1000);
}

// Fonction pour arrêter le timer
function stopTimer() {
    clearInterval(timerInterval);
    return elapsedTime;
}

// Fonction pour reset le timer
function resetTimer() {
    clearInterval(timerInterval);
    elapsedTime = 0;
    timerElement.textContent = '00:00';
}

// Initialise le timer quand la grille est générée
function initializeTimer() {
    resetTimer();
    startTimer();
}

// Ajoute un event pour lancer le timer quand la grille est générée
if (document.getElementById('memoryGrid')) {
    initializeTimer();
}
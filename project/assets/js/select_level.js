// Thème et difficulté du niveau
const themeButtons = document.querySelectorAll('.theme-btn');
const difficultyButtons = document.querySelectorAll('.diff-btn');
const startButton = document.querySelector('.start-game-btn');

let selectedTheme = null;
let selectedDifficulty = null;

// Désélectionner toutes les options au chargement
window.onload = () => {
    // Réinitialiser sessionStorage
    sessionStorage.removeItem('selectedTheme');
    sessionStorage.removeItem('selectedDifficulty');
    
    themeButtons.forEach(btn => btn.classList.remove('active'));
    difficultyButtons.forEach(btn => btn.classList.remove('active'));
    
    if (startButton) {
        startButton.style.backgroundColor = '#666';
        startButton.style.pointerEvents = 'none';
    }
};

// Gestionnaire pour boutons de thème
themeButtons.forEach(button => {
    button.addEventListener('click', () => {
        themeButtons.forEach(btn => btn.classList.remove('active'));
        button.classList.add('active');
        selectedTheme = button.dataset.theme;
        checkSelections();
    });
});

// Gestionnaire pour boutons de difficulté
difficultyButtons.forEach(button => {
    button.addEventListener('click', () => {
        difficultyButtons.forEach(btn => btn.classList.remove('active'));
        button.classList.add('active');
        selectedDifficulty = button.dataset.grid;
        checkSelections();
    });
});

// Vérifier si sélections faites
function checkSelections() {
    if (selectedTheme && selectedDifficulty && startButton) {
        startButton.style.backgroundColor = '#f39c12';
        startButton.style.pointerEvents = 'auto';
        
        // Stocker les sélections dans sessionStorage
        sessionStorage.setItem('selectedTheme', selectedTheme);
        sessionStorage.setItem('selectedDifficulty', selectedDifficulty);
    }
}

// Modifier lien du bouton start pour inclure les paramètres
if (startButton) {
    startButton.addEventListener('click', (e) => {
        if (!selectedTheme || !selectedDifficulty) {
            e.preventDefault();
            alert('Veuillez sélectionner un thème et une difficulté');
        }
    });
}
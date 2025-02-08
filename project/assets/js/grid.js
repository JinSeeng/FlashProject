// * Chemins d'images pour chaque thème (animaux, marques, voitures). */
const themeImagePaths = {
    animals: Array.from({length: 50}, (_, i) => `../../assets/img/animals/animal-${i + 1}.jpg`),
    brands: Array.from({length: 50}, (_, i) => `../../assets/img/brands/brand-${i + 1}.jpg`),
    cars: Array.from({length: 50}, (_, i) => `../../assets/img/cars/cars-${i + 1}.jpg`)
};

// Récupération des paramètres de jeu depuis le sessionStorage
const theme = sessionStorage.getItem('selectedTheme');
const difficulty = parseInt(sessionStorage.getItem('selectedDifficulty'));

/** Variables d'état du jeu */
let selectedCards = []; // tableau contenant les cartes actuellement retournées
let canFlip = true; // booléen indiquant si le joueur peut retourner des cartes
let matchedPairs = 0; // nombre de paires trouvées
let totalMoves = 0; // nombre total de coups joués
const totalPairs = difficulty ? (difficulty * difficulty) / 2 : 0; // nombre total de paires à trouver (calculé en fonction de la difficulté)

function shuffleArray(array) {
    // On parcourt le tableau de la fin vers le début
    for (let i = array.length - 1; i > 0; i--) {
        // On génère un nombre aléatoire entre 0 et le i courant
        const j = Math.floor(Math.random() * (i + 1));
        // On affecte la valeur du tableau sur l'index i au tableau sur l'index j
        [array[i], array[j]] = [array[j], array[i]];
    }
    return array;
}

/** Met à jour l'affichage du nombre de coups */
function updateMovesCount() {
    const movesElement = document.getElementById('moves');
    if (movesElement) {
        movesElement.textContent = totalMoves;
    }
}

/** Sauvegarde le score du joueur via AJAX */
function savePlayerScore(elapsedTime) {
    fetch('../../games/memory/save_score.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            elapsed_time: elapsedTime,
            difficulty: difficulty
        })
    })
    .then(response => response.json())
    .then(data => {
        if (!data.success) {
            console.error('Failed to save score');
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

function checkGameCompletion() {
    if (matchedPairs === totalPairs) {
        const timeString = document.getElementById('time').textContent;
        const [minutes, seconds] = timeString.split(':').map(Number);
        const elapsedTime = minutes * 60 + seconds;
        
        stopTimer(); // Fonction importée depuis timer.js
        savePlayerScore(elapsedTime);
        showGameCompleteModal();
    }
}

/** Affiche la modale de fin de jeu */
function showGameCompleteModal() {
    const modal = document.getElementById('gameCompleteModal');
    modal.classList.add('active');
    document.body.classList.add('modal-open');
}

/** Pour le retournement et l'animation des cartes */
function initializeCardFlipping() {
    const cards = document.querySelectorAll('.card');
    cards.forEach(card => {
        const img = card.querySelector('img');
        img.style.display = 'none';
        card.addEventListener('click', () => {
            // Vérifie si la carte peut être retournée
            if (!canFlip || card.classList.contains('matched') || selectedCards.includes(card)) {
                return;
            }

            totalMoves++;
            updateMovesCount();
            
            // Retourne la carte
            const img = card.querySelector('img');
            card.classList.add('flipped');
            img.style.display = 'block';
            selectedCards.push(card);
            
            // Si deux cartes sont retournées
            if (selectedCards.length === 2) {
                canFlip = false;
                const [card1, card2] = selectedCards;
                const img1 = card1.querySelector('img');
                const img2 = card2.querySelector('img');
                
                // Vérifie si les cartes forment une paire
                if (img1.src === img2.src) {
                    card1.classList.add('matched');
                    card2.classList.add('matched');
                    matchedPairs++;
                    checkGameCompletion();
                } else {
                    // Retourne les cartes face cachée après 1 seconde
                    setTimeout(() => {
                        card1.classList.remove('flipped');
                        card2.classList.remove('flipped');
                        img1.style.display = 'none';
                        img2.style.display = 'none';
                    }, 1000);
                }
                
                // Réinitialise pour le prochain tour
                setTimeout(() => {
                    selectedCards = [];
                    canFlip = true;
                }, 1000);
            }
        });
    });
}

/** Fonction pour génèrer la grille de jeu */
function generateGrid() {
    const grid = document.getElementById('memoryGrid');
    if (!grid) return;
    
    // Vérifie que les paramètres de jeu sont valides
    if (!theme || !difficulty || !themeImagePaths[theme]) {
        window.location.href = '../memory/level.php';
        return;
    }
    
    // Sélectionne et duplique les images nécessaires
    const availableImages = [...themeImagePaths[theme]];
    const selectedImages = shuffleArray(availableImages).slice(0, totalPairs);
    let cardImages = [...selectedImages, ...selectedImages];
    cardImages = shuffleArray(cardImages);
    
    // Réinitialise le compteur de coups
    totalMoves = 0;
    updateMovesCount();
    
    // Configure la grille en fonction de la difficulté
    grid.style.gridTemplateColumns = `repeat(${difficulty}, 1fr)`;
    grid.innerHTML = '';
    
    // Crée les cartes
    cardImages.forEach((imagePath, index) => {
        const card = document.createElement('div');
        card.className = 'card';
        card.dataset.id = index;
        card.dataset.imagePath = imagePath;
        
        const imageElement = document.createElement('img');
        imageElement.src = imagePath;
        imageElement.alt = `Card ${index}`;
        imageElement.style.width = '100%';
        imageElement.style.height = '100%';
        imageElement.style.objectFit = 'cover';
        imageElement.style.display = 'none';
        
        card.appendChild(imageElement);
        grid.appendChild(card);
    });
    
    initializeCardFlipping();
}

// Initialise les boutons de la modale de fin de jeu
function initializeModalButtons() {
    document.getElementById('playAgainBtn').addEventListener('click', function() {
        location.reload();
    });

    document.getElementById('viewScoresBtn').addEventListener('click', function() {
        window.location.href = '../../scores.php';
    });
}

// Initialisation du jeu si la grille existe
if (document.getElementById('memoryGrid')) {
    generateGrid();
    initializeModalButtons();
}
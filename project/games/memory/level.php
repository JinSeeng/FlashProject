<!-- Déclaration du type de document et de la langue -->
<!DOCTYPE html>
<html lang="fr">
<head>
  <!-- Configuration de l'encodage et de l'affichage sur mobile -->
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sélection du niveau - The Power Of Memory</title>

  <!-- Importation des fichiers CSS pour le style -->
  <link rel="stylesheet" href="../../assets/css/style.css">
  <link rel="stylesheet" href="../../assets/css/header.css">
  <link rel="stylesheet" href="../../assets/css/footer.css">
  <link rel="stylesheet" href="../../assets/css/level.css">
  <!-- Importation des icônes Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<!-- Ajout de la classe auth-page pour le style spécifique -->
<body class="auth-page">

<?php 
    include('../../partials/header.php'); 
    require_once ('../../utils/common.php');
?>
<!-- Contenu principal de la page -->
<main class="game-page">
  <!-- Section des options de jeu -->
  <section class="game-options">
    <div class="options-container">
      <h1>MEMORY</h1>
      <!-- Sélecteur de thème -->
      <div class="theme-selector">
        <h2>Choisissez votre thème</h2>
        <div class="theme-buttons">
          <!-- Boutons de thème avec icônes -->
          <button class="theme-btn active" data-theme="animals">
            <i class="fas fa-paw"></i>
            Animaux
          </button>
          <button class="theme-btn" data-theme="brands">
            <i class="fas fa-copyright"></i>
            Marques
          </button>
          <button class="theme-btn" data-theme="cars">
            <i class="fas fa-car"></i>
            Voitures
          </button>
        </div>
      </div>

      <!-- Sélecteur de difficulté -->
      <div class="difficulty-selector">
        <h2>Choisissez la difficulté</h2>
        <div class="difficulty-buttons">
          <!-- Boutons de difficulté avec tailles de grille -->
          <button class="diff-btn active" data-grid="4">
            Facile (4x4)
          </button>
          <button class="diff-btn" data-grid="6">
            Intermédiaire (6x6)
          </button>
          <button class="diff-btn" data-grid="8">
            Expert (8x8)
          </button>
          <button class="diff-btn" data-grid="10">
            Impossible (10x10)
          </button>
        </div>
      </div>

      <!-- Bouton pour démarrer la partie -->
      <a href="../../games/memory/index.php" class="start-game-btn" style="text-decoration: none; display: inline-block; text-align: center;">COMMENCER LA PARTIE</a>
    </div>
  </section>

  <!-- Section du plateau de jeu (cachée initialement) -->
  <section class="game-board hidden">
    <!-- Informations de jeu (timer et score) -->
    <div class="game-info">
      <div class="timer">
        <i class="fas fa-clock"></i>
        <span>00:00</span>
      </div>
      <div class="score">
        <i class="fas fa-star"></i>
        <span>Score: 0</span>
      </div>
    </div>
    <!-- Conteneur pour la grille de jeu -->
    <div class="grid-container">
      <!-- Grille générée plus tard en JavaScript -->
    </div>
  </section>
</main>

<?php include('../../partials/footer.php'); ?>
<script src="../../assets/js/select_level.js"></script>
<script src="../../assets/js/grid.js"></script>
</body>
</html>

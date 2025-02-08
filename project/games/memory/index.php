<?php
session_start();
require_once '../../utils/database.php';
require_once ('../../utils/common.php');
require_once ('../../games/memory/save_score.php');
$pdo = connectToDbAndGetPdo();

// Inclure chat.php
require_once __DIR__ . '/../../chat.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Memory Game - The Power Of Memory</title>

    <!-- CSS -->
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/header.css">
    <link rel="stylesheet" href="../../assets/css/footer.css">
    <link rel="stylesheet" href="../../assets/css/memory.css">
    <link rel="stylesheet" href="../../assets/css/chat.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="auth-page">
    <?php include('../../partials/header.php'); ?>

    <main class="game-container">
        <div class="game-info">
            <div class="timer">Temps: <span id="time">00:00</span></div>
            <div class="moves">Coups: <span id="moves">0</span></div>
        </div>

        <div class="memory-grid" id="memoryGrid">
            <!-- Grille générée dynamiquement -->
        </div>

            <div class="success-modal" id="gameCompleteModal">
        <div class="success-modal-content">
            <h2>Félicitations !</h2>
            <p>Vous avez gagné la partie</p>
            <div class="modal-buttons">
                <a href="../../games/memory/level.php" class="btn-primary">Refaire une partie</a>
                <a href="../../games/memory/scores.php" class="btn-primary">Voir les scores</a>
                <a href="/index.php" class="btn-primary">Retour à l'accueil</a>
            </div>
        </div>
    </div>

        <?php require_once __DIR__ . '/../../chat.php';?>
        
    </main>

    <?php include('../../partials/footer.php'); ?>
    <script src="../../assets/js/select_level.js"></script>
    <script src="../../assets/js/grid.js"></script>
    <script src="../../assets/js/timer.js"></script>
</body>
</html>

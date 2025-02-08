<?php
require_once '../../utils/database.php';
require_once '../../utils/common.php';

function savePlayerScore($difficulty, $elapsed_time) {
    // Vérifie si le joueur est connecté
    if (!isset($_SESSION['user_id'])) {
        return false;
    }

    try {
        $pdo = connectToDbAndGetPdo();
        
        // Pour la difficulté choisie
        $difficultyMap = [
            4 => 'Facile',
            6 => 'Moyen', 
            8 => 'Difficile', 
            10 => 'Expert'
        ];

        $difficultyLabel = $difficultyMap[$difficulty] ?? 'Master';

        // Vérifie d'abord si un score identique existe déjà pour ce joueur dans cette difficulté
        $checkStmt = $pdo->prepare("
            SELECT COUNT(*) 
            FROM score 
            WHERE id_utilisateur = :id_utilisateur 
            AND id_jeu = 1 
            AND difficulte = :difficulte 
            AND score = :score
        ");

        $checkStmt->execute([
            ':id_utilisateur' => $_SESSION['user_id'],
            ':difficulte' => $difficultyLabel,
            ':score' => $elapsed_time
        ]);

        $exists = $checkStmt->fetchColumn() > 0;

        // Si le score n'existe pas déjà, on l'ajoute
        if (!$exists) {
            $insertStmt = $pdo->prepare("
                INSERT INTO score (id_utilisateur, id_jeu, difficulte, score, date_partie) 
                VALUES (:id_utilisateur, 1, :difficulte, :score, NOW())
            ");
            
            $insertStmt->execute([
                ':id_utilisateur' => $_SESSION['user_id'],
                ':difficulte' => $difficultyLabel,
                ':score' => $elapsed_time
            ]);
            
            return true;
        }

        return false; // Retourne false si le score existe déjà

    } catch (Exception $e) {
        error_log("Error saving score: " . $e->getMessage());
        return false;
    }
}

// Requêtes en AJAX pour enregistrer le score
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (isset($data['elapsed_time']) && isset($data['difficulty'])) {
        $result = savePlayerScore($data['difficulty'], $data['elapsed_time']);
        echo json_encode([
            'success' => $result,
            'message' => $result ? 'Score sauvegardé avec succès' : 'Score identique déjà existant ou erreur'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Données manquantes'
        ]);
    }
    exit();
}
?>
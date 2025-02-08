<?php
session_start();
require_once '../../utils/database.php';

if (!isset($_SESSION['user_id']) || !isset($_POST['message'])) {
    header('Location: index.php');
    exit();
}

date_default_timezone_set('Europe/Paris');

$pdo = connectToDbAndGetPdo();
$message = trim($_POST['message']);

if (!empty($message)) {
    try {
        $stmt = $pdo->prepare("INSERT INTO message (id_jeu, id_expediteur, message, date_message) VALUES (1, ?, ?, NOW())");
        $stmt->execute([$_SESSION['user_id'], htmlspecialchars($message)]);
        
        $data = [
            'success' => true,
            'message' => $message,
            'username' => $_SESSION['user_pseudo'],
            'time' => date('H:i'),
            'date' => date('Y-m-d')
        ];
        echo json_encode($data);
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'error' => 'Erreur d\'envoi']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Message vide']);
}
exit();
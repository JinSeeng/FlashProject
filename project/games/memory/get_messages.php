<?php
session_start();
require_once '../../utils/database.php';

if (!isset($_SESSION['user_id'])) {
    exit(json_encode([]));
}

date_default_timezone_set('Europe/Paris');

$pdo = connectToDbAndGetPdo();
$lastTime = isset($_GET['last']) ? $_GET['last'] : '00:00';
$lastDate = isset($_GET['lastDate']) ? $_GET['lastDate'] : date('Y-m-d');

$stmt = $pdo->prepare("
    SELECT
        m.message AS contenu_du_message,
        u.pseudo AS nom_joueur,
        m.date_message,
        m.id_expediteur = ? AS isSender
    FROM message m
    JOIN utilisateur u ON m.id_expediteur = u.id
    WHERE m.date_message >= NOW() - INTERVAL 24 HOUR
    AND (DATE(m.date_message) > ? OR (DATE(m.date_message) = ? AND TIME(m.date_message) > ?))
    ORDER BY m.date_message ASC
");

$stmt->execute([$_SESSION['user_id'], $lastDate, $lastDate, $lastTime]);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

$formattedMessages = array_map(function($msg) {
    return [
        'message' => $msg['contenu_du_message'],
        'username' => $msg['nom_joueur'],
        'time' => date('H:i', strtotime($msg['date_message'])),
        'date' => date('Y-m-d', strtotime($msg['date_message'])),
        'isSender' => (bool)$msg['isSender']
    ];
}, $messages);

echo json_encode($formattedMessages);
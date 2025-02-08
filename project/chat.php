<?php
if (!isset($_SESSION['user_id'])) {
    return; // Ne pas afficher le chat si l'utilisateur n'est pas connecté
}

// Configuration du fuseau horaire
date_default_timezone_set('Europe/Paris');

// Récupération du GIF
$gifs = file_get_contents('https://api.thecatapi.com/v1/images/search?mime_types=gif');
$gifs = json_decode($gifs, true);
$gif = $gifs[0]["url"];
$_SESSION['gif'] = $gif;

// Fonction pour formater la date en français
function formatDateFr($date) {
    $timestamp = strtotime($date);
    $today = strtotime('today');
    $yesterday = strtotime('yesterday');
    
    if ($timestamp >= $today) {
        return "Aujourd'hui";
    } elseif ($timestamp >= $yesterday) {
        return "Hier";
    } else {
        setlocale(LC_TIME, 'fr_FR.UTF8', 'fr.UTF8', 'fr_FR.UTF-8', 'fr.UTF-8');
        return strftime('%A %d %B %Y', $timestamp);
    }
}
?>

<!-- Bouton pour ouvrir le chat -->
<div class="chat-button" onclick="toggleChat()">
    <i class="fas fa-comments"></i>
</div>

<!-- Fenêtre de chat -->
<div class="chat-window" id="chatWindow">
    <div class="chat-header">
        <h4>Chat en direct</h4>
        <span onclick="toggleChat()" style="cursor: pointer;">×</span>
    </div>
    <div class="chat-messages" id="chatMessages">
        <?php
        if (isset($_SESSION['gif'])) {
            echo "<div class='chat-bubble bot'>";
            echo "<img src='" . htmlspecialchars($_SESSION['gif']) . "' alt='GIF' style='max-width: 100%; height: auto;'>";
            echo "</div>";
            unset($_SESSION['gif']);
        }

        $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
        if ($userId) {
            // Requête pour récupérer les messages des dernières 24 heures
            $sql = "SELECT
                message.message AS contenu_du_message,
                utilisateur.pseudo AS nom_joueur,
                message.date_message AS date_message,
                message.id_expediteur = ? AS isSender,
                DATE(message.date_message) AS message_date
            FROM message
            JOIN utilisateur ON message.id_expediteur = utilisateur.id
            WHERE message.date_message >= NOW() - INTERVAL 1 DAY
            ORDER BY message.date_message ASC";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([$userId]);

            $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $currentDate = '';

            foreach ($messages as $message) {
                $messageClass = $message['isSender'] ? 'user' : 'other';
                $messageDate = date('Y-m-d', strtotime($message['date_message']));
                $time = date('H:i', strtotime($message['date_message']));

                // Afficher le séparateur de date uniquement s'il change
                if ($messageDate !== $currentDate) {
                    $displayDate = formatDateFr($messageDate);
                    echo "<div class='date-separator-wrapper'>";
                    echo "<div class='date-separator' data-date='{$messageDate}'>{$displayDate}</div>";
                    echo "</div>";
                    $currentDate = $messageDate;
                }

                // Affichage du message
                echo "<div class='chat-bubble {$messageClass}'>";
                echo "<span class='username'>" . htmlspecialchars($message['nom_joueur']) . "</span>";
                echo "<span class='message-content'>" . htmlspecialchars($message['contenu_du_message']) . "</span>";
                echo "<span class='timestamp'>{$time}</span>";
                echo "</div>";
            }
        }
        ?>
    </div>
    <div class="chat-input-container">
        <form class="chat-input" method="POST" action="send_message.php">
            <input type="text" autocomplete="off" name="message" placeholder="Votre message..." required>
            <button type="submit">
                <i class="fas fa-paper-plane"></i>
            </button>
        </form>
    </div>
</div>

<script src="../../assets/js/chat.js"></script>
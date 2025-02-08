<?php
function connectToDbAndGetPdo(): ?PDO
{
    $db_host = '127.0.0.1';
    $db_user = 'root';
    $db_password = 'root';
    $db_db = 'power_of_memory';
    $db_port = 8889;

    try {
        // Création de la connexion
        $conn = new PDO("mysql:host=$db_host;dbname=$db_db;port=$db_port;charset=utf8mb4", $db_user, $db_password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $conn;

    } catch (PDOException $e) {
        // Afficher un message d'erreur et retourner null en cas d'échec
        echo "Erreur de connexion : " . $e->getMessage();
        return null;
    }
}
?>
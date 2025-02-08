<?php
require_once 'utils/common.php';
require_once 'utils/database.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: /login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_photo'])) {
    $userId = $_SESSION['user_id'];
    $file = $_FILES['profile_photo'];
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    $maxFileSize = 5 * 1024 * 1024; // 5MB
    $errors = [];

    // Vérification du type de fichier
    if (!in_array($file['type'], $allowedTypes)) {
        $errors[] = "Le type de fichier n'est pas autorisé. Types acceptés : JPG, PNG, GIF.";
    }

    // Vérification de la taille du fichier
    if ($file['size'] > $maxFileSize) {
        $errors[] = "Le fichier est trop volumineux. Taille maximum : 5MB.";
    }

    if (empty($errors)) {
        // Créer le dossier utilisateur s'il n'existe pas
        $userDir = "userFiles/" . $userId;
        if (!file_exists($userDir)) {
            mkdir($userDir, 0777, true);
        }

        // Générer un nom de fichier unique
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $newFileName = uniqid() . '.' . $extension;
        $targetPath = $userDir . '/' . $newFileName;

        // Supprimer l'ancienne photo si elle existe
        $pdo = connectToDbAndGetPdo();
        $stmt = $pdo->prepare("SELECT photo FROM utilisateur WHERE id = :id");
        $stmt->execute(['id' => $userId]);
        $oldPhoto = $stmt->fetchColumn();

        if ($oldPhoto && file_exists($userDir . '/' . $oldPhoto)) {
            unlink($userDir . '/' . $oldPhoto);
        }

        // Déplacer et enregistrer le nouveau fichier
        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            // Mettre à jour la base de données
            $stmt = $pdo->prepare("UPDATE utilisateur SET photo = :photo WHERE id = :id");
            if ($stmt->execute(['photo' => $newFileName, 'id' => $userId])) {
                $_SESSION['user_photo'] = $newFileName;
                $_SESSION['success'] = "Photo de profil mise à jour avec succès.";
            } else {
                $_SESSION['error'] = "Erreur lors de la mise à jour de la photo dans la base de données.";
            }
        } else {
            $_SESSION['error'] = "Erreur lors du téléchargement du fichier.";
        }
    } else {
        $_SESSION['error'] = implode("<br>", $errors);
    }
}

// Redirection vers la page du compte
header('Location: /myAccount.php');
exit();
?>
<?php
require_once 'database.php';

function validateRegistration($email, $pseudo, $password, $confirm_password, &$errors) {
    // Validation du format d'email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Format d'email invalide.";
    }

    // Validation de la longueur et de la (non-)existence du pseudo
    if (strlen($pseudo) < 4) {
        $errors[] = "Le pseudo doit faire au moins 4 caractères.";
    } elseif (isUserWithPseudoExists($pseudo)) {
        $errors[] = "Ce pseudo est déjà utilisé.";
    }

    // Validation de la complexité du mot de passe
    if (!isPasswordValid($password)) {
        $errors[] = "Le mot de passe doit faire au moins 8 caractères, contenir au moins un chiffre, une majuscule et un caractère spécial.";
    } elseif ($password !== $confirm_password) {
        $errors[] = "Les mots de passe ne correspondent pas.";
    }
}

function isUserWithPseudoExists($pseudo) {
    $pdo = connectToDbAndGetPdo();
    if ($pdo) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM utilisateur WHERE pseudo = :pseudo");
        $stmt->bindParam(':pseudo', $pseudo);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }
    return false;
}

function isPasswordValid($password) {
    return strlen($password) >= 8 && preg_match('/[0-9]/', $password) && preg_match('/[A-Z]/', $password) && preg_match('/[^a-zA-Z0-9]/', $password);
}

function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

function createUser($email, $pseudo, $hashed_password) {
    $pdo = connectToDbAndGetPdo();
    if ($pdo) {
        $stmt = $pdo->prepare("INSERT INTO utilisateur (email, pseudo, mot_de_passe, date_inscription) VALUES (:email, :pseudo, :password, NOW())");
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':pseudo', $pseudo);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->execute();
    }
}

function showSuccessModal() {
    echo '<div class="success-modal">';
    echo '  <div class="success-modal-content">';
    echo '    <h2>Inscription réussie !</h2>';
    echo '    <p>Vous pouvez maintenant vous connecter avec vos identifiants.</p>';
    echo '    <a href="login.php" class="btn-primary">Se connecter</a>';
    echo '  </div>';
    echo '</div>';
}

function verifyLogin($email, $password) {
    $pdo = connectToDbAndGetPdo();
    if ($pdo) {
        $stmt = $pdo->prepare("SELECT id, pseudo, email, mot_de_passe FROM utilisateur WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['mot_de_passe'])) {
            // Stocker les informations en session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_pseudo'] = $user['pseudo'];
            $_SESSION['user_email'] = $user['email'];
            return $user['id'];
        }
    }
    return false;
}

function showLoginSuccessModal() {
    echo '<div class="success-modal">';
    echo '    <div class="success-modal-content">';
    echo '        <h2>Connexion réussie !</h2>';
    echo '        <p>Vous êtes maintenant connecté à votre compte.</p>';
    echo '        <div class="modal-buttons">';
    echo '            <a href="/index.php" class="btn-primary">Retour à la page d\'accueil</a>';
    echo '            <a href="/myAccount.php" class="btn-primary">Mon compte</a>';
    echo '        </div>';
    echo '    </div>';
    echo '</div>';
}
?>
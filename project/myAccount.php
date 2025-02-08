<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once 'utils/common.php';
require_once 'utils/userConnexion.php';
require_once 'utils/database.php';

function updatePassword($userId, $oldPassword, $newPassword) {
    $pdo = connectToDbAndGetPdo();
    if (!$pdo) {
        $_SESSION['error'] = "Erreur de connexion à la base de données";
        return false;
    }

    // Vérifier l'ancien mot de passe
    $stmt = $pdo->prepare("SELECT mot_de_passe FROM utilisateur WHERE id = :id");
    $stmt->execute(['id' => $userId]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($oldPassword, $user['mot_de_passe'])) {
        $_SESSION['error'] = "L'ancien mot de passe est incorrect";
        return false;
    }

    // Vérifier si le nouveau mot de passe est valide
    if (!isPasswordValid($newPassword)) {
        $_SESSION['error'] = "Le nouveau mot de passe doit faire au moins 8 caractères, contenir au moins un chiffre, une majuscule et un caractère spécial";
        return false;
    }

    // Hasher et mettre à jour le nouveau mot de passe
    try {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE utilisateur SET mot_de_passe = :password WHERE id = :id");
        $stmt->execute([
            'password' => $hashedPassword,
            'id' => $userId
        ]);
        return true;
    } catch (PDOException $e) {
        $_SESSION['error'] = "Erreur lors de la mise à jour du mot de passe";
        return false;
    }
}

function updateEmail($userId, $oldEmail, $newEmail, $password) {
    $pdo = connectToDbAndGetPdo();
    if (!$pdo) {
        $_SESSION['error'] = "Erreur de connexion à la base de données";
        return false;
    }

    // Vérifier les credentials
    $stmt = $pdo->prepare("SELECT mot_de_passe FROM utilisateur WHERE id = :id AND email = :email");
    $stmt->execute([
        'id' => $userId,
        'email' => $oldEmail
    ]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($password, $user['mot_de_passe'])) {
        $_SESSION['error'] = "Le mot de passe est incorrect";
        return false;
    }

    // Vérifier si le nouveau email est valide
    if (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Format d'email invalide";
        return false;
    }

    // Vérifier si le nouvel email n'est pas déjà utilisé
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM utilisateur WHERE email = :email AND id != :id");
    $stmt->execute([
        'email' => $newEmail,
        'id' => $userId
    ]);
    
    if ($stmt->fetchColumn() > 0) {
        $_SESSION['error'] = "Cet email est déjà utilisé";
        return false;
    }

    // Mettre à jour l'email
    try {
        $stmt = $pdo->prepare("UPDATE utilisateur SET email = :email WHERE id = :id");
        $success = $stmt->execute([
            'email' => $newEmail,
            'id' => $userId
        ]);

        if ($success) {
            $_SESSION['user_email'] = $newEmail;
            return true;
        }
        return false;
    } catch (PDOException $e) {
        $_SESSION['error'] = "Erreur lors de la mise à jour de l'email";
        return false;
    }
}

    // Traitement des formulaires
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['update_password'])) {
            $oldPassword = $_POST['old_password'];
            $newPassword = $_POST['new_password'];
            $confirmPassword = $_POST['confirm_password'];

            if ($newPassword !== $confirmPassword) {
                $_SESSION['error'] = "Les nouveaux mots de passe ne correspondent pas.";
            } elseif (updatePassword($_SESSION['user_id'], $oldPassword, $newPassword)) {
                $_SESSION['success'] = "Mot de passe mis à jour avec succès.";
            } else {
                $_SESSION['error'] = "Erreur lors de la mise à jour du mot de passe.";
            }
        } elseif (isset($_POST['update_email'])) {
            $oldEmail = $_POST['old_email'];
            $newEmail = $_POST['new_email'];
            $password = $_POST['email_password'];

            if (updateEmail($_SESSION['user_id'], $oldEmail, $newEmail, $password)) {
                $_SESSION['success'] = "Email mis à jour avec succès.";
            } else {
                $_SESSION['error'] = "Erreur lors de la mise à jour de l'email.";
            }
        }
        
        header('Location: /myAccount.php');
        exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Compte - The Power Of Memory</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <link rel="stylesheet" href="assets/css/account.css">
    <link rel="stylesheet" href="assets/css/password.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="auth-page">
    <?php include "partials/header.php"; ?>

        <div class="success-modal" id="successModal">
        <div class="success-modal-content">
            <h2 id="modalTitle">Succès</h2>
            <p id="modalMessage"></p>
            <div class="modal-buttons">
                <button class="btn-primary" onclick="closeModal()">OK</button>
            </div>
        </div>
    </div>
    <main>
        <section class="account-section">
        <?php
                // Établir la connexion à la base de données
                $pdo = connectToDbAndGetPdo();

                // Récupérer le pseudo de l'utilisateur
                $stmt = $pdo->prepare("SELECT pseudo FROM utilisateur WHERE id = :id");
                $stmt->execute(['id' => $_SESSION['user_id']]);
                $userPseudo = $stmt->fetchColumn();
                ?>
                <div class="account-container">
                <div class="section-header">
                    <h1>Mon compte</h1>
                    <p>Bienvenue, <?php echo htmlspecialchars($userPseudo); ?> !</p>
                    <div class="ornament">
                        <div class="ornament-line"></div>
                        <div class="ornament-symbol">♦</div>
                        <div class="ornament-line"></div>
                    </div>
                </div>

                <div class="profile-section">
                    <div class="profile-image">
                        <?php
                        $userId = $_SESSION['user_id'];
                        $pdo = connectToDbAndGetPdo();
                        $stmt = $pdo->prepare("SELECT photo FROM utilisateur WHERE id = :id");
                        $stmt->execute(['id' => $userId]);
                        $userPhoto = $stmt->fetchColumn();
                        
                        $photoPath = $userPhoto 
                            ? "userFiles/" . $userId . "/" . $userPhoto
                            : "https://cdn-icons-png.flaticon.com/512/3135/3135768.png";
                        ?>
                        <img src="<?php echo htmlspecialchars($photoPath); ?>" alt="Profile" id="profile-preview">
                        
                        <form action="update_photo.php" method="POST" enctype="multipart/form-data">
                            <label for="profile-upload" class="upload-btn">
                                <i class="fas fa-camera"></i>
                                <span>Modifier la photo</span>
                            </label>
                            <input type="file" name="profile_photo" id="profile-upload" accept="image/*" hidden>
                            <button type="submit" id="submit-photo" style="display: none;">Enregistrer</button>
                        </form>
                    </div>

                    <div class="account-forms">
                        <form class="account-form password-form" method="POST" action="myAccount.php">
                            <h2>Modifier le mot de passe</h2>
                            <div class="form-group">
                                <input type="password" name="old_password" placeholder="Ancien mot de passe" required>
                            </div>
                            <div class="form-group">
                                <input type="password" id="new_password" name="new_password" placeholder="Nouveau mot de passe" required>
                            </div>
                            <div class="form-group">
                                <input type="password" name="confirm_password" placeholder="Confirmer le nouveau mot de passe" required>
                            </div>
                            <input type="hidden" name="update_password" value="1">
                            <button type="submit" class="btn-primary">Mettre à jour le mot de passe</button>
                        </form>

                        <form class="account-form email-form" method="POST" action="myAccount.php">
                            <h2>Modifier l'email</h2>
                            <div class="form-group">
                                <input type="email" name="old_email" placeholder="Ancien email" value="<?php echo htmlspecialchars($_SESSION['user_email']); ?>" required>
                            </div>
                            <div class="form-group">
                                <input type="email" name="new_email" placeholder="Nouvel email" required>
                            </div>
                            <div class="form-group">
                                <input type="password" name="email_password" placeholder="Mot de passe" required>
                            </div>
                            <input type="hidden" name="update_email" value="1">
                            <button type="submit" class="btn-primary">Mettre à jour l'email</button>
                        </form>
                    </div>
                </div>

                <div class="logout-section">
                    <a href="disconnect.php" class="btn-logout">
                        <i class="fas fa-sign-out-alt"></i>
                        Déconnexion
                    </a>
                </div>
            </div>
        </section>
    </main>

    <?php include "partials/footer.php"; ?>

    <script src="assets/js/password-strength.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Ajouter un système de mesure de force du MDP
        addPasswordStrengthMeter('new_password')
        });

        document.getElementById('profile-upload').addEventListener('change', function() {
            document.getElementById('submit-photo').click();
        });

        // Fonction pour afficher la modale
        function showModal(message, isError = false) {
            const modal = document.getElementById('successModal');
            const modalTitle = document.getElementById('modalTitle');
            const modalMessage = document.getElementById('modalMessage');
            
            modalTitle.textContent = isError ? "Erreur" : "Succès";
            if (isError) {
                modalTitle.style.color = '#e74c3c'; // Rouge pour les erreurs
            } else {
                modalTitle.style.color = '#e67e22'; // Couleur originale pour les succès
            }
            
            modalMessage.textContent = message;
            modal.style.display = 'flex';
            document.body.classList.add('modal-open');
        }

        // Fonction pour fermer la modale
        function closeModal() {
            const modal = document.getElementById('successModal');
            document.body.classList.remove('modal-open');
            modal.style.display = 'none';
        }

        <?php if (isset($_SESSION['success'])): ?>
            showModal("<?php echo addslashes($_SESSION['success']); ?>", false);
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            showModal("<?php echo addslashes($_SESSION['error']); ?>", true);
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
    </script>
</body>
</html>

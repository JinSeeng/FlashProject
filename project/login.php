<?php
require_once 'utils/common.php';
require_once 'utils/userConnexion.php';

// Initialiser la variable d'erreur
$loginError = '';
$showSuccessModal = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (verifyLogin($email, $password)) {
        $showSuccessModal = true;
    } else {
        $loginError = "Email ou mot de passe incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - The Power Of Memory</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <link rel="stylesheet" href="assets/css/register_login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    </head>
<body>
    <?php include "partials/header.php"; ?>
    <main>
        <section class="auth-section">
            <div class="auth-container">
                <h1>CONNEXION</h1>
                <?php if ($loginError): ?>
                    <div class="error-message">
                        <p class="input-error"><?php echo htmlspecialchars($loginError); ?></p>
                    </div>
                <?php endif; ?>
                <form class="auth-form" method="POST" action="">
                    <input type="email" id="email" name="email" 
                        value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" 
                        placeholder="Email" required>
                    <input type="password" id="password" name="password" 
                        placeholder="Mot de passe" required>
                    <button type="submit" class="btn-primary">Me connecter</button>
                    <div class="auth-links">
                        <a href="forgot_password.php" class="forgot-password">Mot de passe oublié ?</a>
                        <a href="register.php" class="register-link">Vous n'avez pas encore de compte ? Inscrivez-vous</a>
                    </div>
                </form>
        </section>
    </main>
        <div class="success-modal" id="successModal" style="display: none;">
        <div class="success-modal-content">
            <h2>Connexion réussie !</h2>
            <p>Vous êtes maintenant connecté.</p>
            <div class="modal-buttons">
                <a href="index.php" class="btn-primary">Retour à l'accueil</a>
                <a href="myAccount.php" class="btn-primary">Voir mon compte</a>
            </div>
        </div>
    </div>
    <?php include "partials/footer.php"; ?>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Gestion de l'affichage de la modale
        const successModal = document.getElementById('successModal');
        
        <?php if ($showSuccessModal): ?>
            successModal.style.display = 'flex';
            document.body.classList.add('modal-open');
        <?php endif; ?>
    });  
    </script>
    <script src="../../assets/js/password.js"></script>
</body>
</html>
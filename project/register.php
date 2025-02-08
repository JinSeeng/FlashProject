<?php
require_once 'utils/userConnexion.php';
$errors = array();
$showSuccessModal = false; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $pseudo = trim($_POST["pseudo"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    validateRegistration($email, $pseudo, $password, $confirm_password, $errors);

    if (empty($errors)) {
        $hashed_password = hashPassword($password);
        createUser($email, $pseudo, $hashed_password);
        $showSuccessModal = true; 
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - The Power Of Memory</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <link rel="stylesheet" href="assets/css/register_login.css">
    <link rel="stylesheet" href="assets/css/password.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="auth-page">
    <?php include "partials/header.php"; ?>

    <main>
        <section class="signup-section">
            <div class="auth-container">
                <h1>INSCRIPTION</h1>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" class="auth-form">
                    <input type="email" id="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" placeholder="Email" required>
                    <input type="text" id="pseudo" name="pseudo" value="<?php echo isset($_POST['pseudo']) ? htmlspecialchars($_POST['pseudo']) : ''; ?>" placeholder="Pseudo" required>
                    <input type="password" id="password" name="password" placeholder="Mot de passe" required>
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirmer le mot de passe" required>
                    
                    <button type="submit" class="btn-primary">Créer mon compte</button>
                </form>
                <?php if (!empty($errors)) { ?>
                <div class="error-message">
                    <?php foreach ($errors as $error) { ?>
                        <p class="input-error"><?php echo $error; ?></p>
                    <?php } ?>
                </div>
                <?php } ?>
            </div>
        </section>
    </main>
    
    <div class="success-modal" id="successModal" style="display: none;">
        <div class="success-modal-content">
            <h2>Inscription réussie !</h2>
            <p>Votre compte a été créé avec succès.</p>
            <div class="modal-buttons">
                <a href="login.php" class="btn-primary">Se connecter</a>
            </div>
        </div>
    </div>
    
    <?php include "partials/footer.php"; ?>

    <script src="assets/js/password-strength.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Gestion de l'affichage de la modale
        const successModal = document.getElementById('successModal');
        
        <?php if ($showSuccessModal): ?>
            successModal.style.display = 'flex';
            document.body.classList.add('modal-open');
        <?php endif; ?>

        // Add password strength meter
        addPasswordStrengthMeter('password');
    });  
    </script>
</body>
</html>
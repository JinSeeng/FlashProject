<?php
session_start();
require_once 'utils/database.php';
require_once 'utils/common.php';

$showModal = false;
$modalMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $subject = $_POST["subject"];
    $message = $_POST["message"];

    // Validation des données
    $errors = [];
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "L'email n'est pas valide.";
    }
    if (empty($name)) {
        $errors[] = "Le nom ne peut pas être vide.";
    }
    if (empty($subject)) {
        $errors[] = "Le sujet ne peut pas être vide.";
    }
    if (empty($message)) {
        $errors[] = "Le message ne peut pas être vide.";
    }

    if (empty($errors)) {
        // Envoi du mail
        $to = "support@powerofmemory.com";
        $headers = "From: " . $email;
        if (mail($to, $subject, $message, $headers)) {
            $showModal = true;
            $modalMessage = "Votre message a été envoyé avec succès !";
        } else {
            $showModal = true;
            $modalMessage = "Une erreur est survenue lors de l'envoi du message.";
        }
    } else {
        // Affichage des erreurs
        foreach ($errors as $error) {
            echo "<p class='error'>" . $error . "</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/header.css">
  <link rel="stylesheet" href="assets/css/footer.css">
  <link rel="stylesheet" href="assets/css/contact.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <title>Contact - The Power Of Memory</title>
</head>
<body>

<?php include "partials/header.php"; ?>

<main>
  <section class="contact-hero">
    <h1>Nous Contacter</h1>
  </section>

  <div class="contact-info-grid">
    <div class="contact-info-box">
      <div class="icon">
        <i class="fas fa-phone"></i>
      </div>
      <p>06 05 04 03 02</p>
    </div>
    <div class="contact-info-box">
      <div class="icon">
        <i class="fas fa-envelope"></i>
      </div>
      <p>support@powerofmemory.com</p>
    </div>
    <div class="contact-info-box">
      <div class="icon">
        <i class="fas fa-map-marker-alt"></i>
      </div>
      <p>Paris</p>
    </div>
  </div>

  <div class="contact-form-section">
    <form class="contact-form" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <input type="text" name="name" placeholder="Nom" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="text" name="subject" placeholder="Sujet" required>
        <textarea name="message" placeholder="Message" required></textarea>
        <button type="submit">Envoyer</button>
    </form>
  </div>
</main>

<?php include "partials/footer.php"; ?>

<!-- Fenêtre modale de succès -->
<div id="successModal" class="success-modal" style="display: <?php echo $showModal ? 'flex' : 'none'; ?>;">
  <div class="success-modal-content">
    <h2>Message envoyé</h2>
    <p><?php echo $modalMessage; ?></p>
    <div class="modal-buttons">
      <button class="btn-primary" onclick="closeModal()">Fermer</button>
    </div>
  </div>
</div>

<script>
function closeModal() {
  document.getElementById('successModal').style.display = 'none';
  document.body.classList.remove('modal-open');
}

// Afficher la modale si un message a été envoyé
<?php if ($showModal): ?>
  document.body.classList.add('modal-open');
<?php endif; ?>
</script>

</body>
</html>
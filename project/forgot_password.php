<!-- Déclaration du type de document et de la langue -->
<!DOCTYPE html>
<html lang="fr">
<head>
  <!-- Configuration de l'encodage et de l'affichage sur mobile -->
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mot de passe oublié - The Power Of Memory</title>

  <!-- Importation des fichiers CSS pour le style -->
  <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <link rel="stylesheet" href="assets/css/register_login.css">
  <!-- Importation des icônes Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<!-- Ajout de la classe auth-page pour le style spécifique de la page -->
<body class="auth-page">

<?php include('partials/header.php'); ?>

<!-- Contenu principal de la page -->
<main>
  <!-- Section de récupération de mot de passe -->
  <section class="auth-section">
    <div class="auth-container">
      <h1>MOT DE PASSE OUBLIÉ</h1>
      <!-- Formulaire de récupération de mot de passe -->
      <form class="auth-form">
        <input type="email" placeholder="Email" required>
        <button type="submit" class="btn-primary">Envoyer</button>
        <!-- Message d'information pour l'utilisateur -->
        <div class="auth-links">
          <p>Nous enverrons un code de vérification à cette adresse email si un compte existant correspond.</p>
        </div>  
        </form>
    </div>
  </section>
</main>

<?php include('partials/footer.php'); ?>

</body>
</html>
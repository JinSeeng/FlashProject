<?php
// Configuration de la connexion à la base de données
try {
  $pdo = new PDO(
    "mysql:host=localhost:8889;dbname=power_of_memory;charset=utf8", // Notez le port 8889 pour MAMP
    "root",
    "root");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
    exit;
}


// Fonction pour récupérer les statistiques
function getStatistics($pdo) {
    $stats = [];
    
    // Nombre total de parties jouées
    $query = "SELECT COUNT(*) as total_games FROM score";
    $stmt = $pdo->query($query);
    $stats['parties_jouees'] = $stmt->fetch()['total_games'];
    
    // Nombre de joueurs inscrits
    $query = "SELECT COUNT(*) as total_users FROM utilisateur";
    $stmt = $pdo->query($query);
    $stats['joueurs_inscrits'] = $stmt->fetch()['total_users'];
    
    // Nombre de joueurs connectés dans les dernières 24h
    $query = "SELECT COUNT(*) as connected_users 
              FROM utilisateur 
              WHERE derniere_connexion >= NOW() - INTERVAL 24 HOUR";
    $stmt = $pdo->query($query);
    $stats['joueurs_connectes'] = $stmt->fetch()['connected_users'];
    
    // Meilleur temps (le plus petit score représente le meilleur temps)
    $query = "SELECT MIN(score) as best_time FROM score";
    $stmt = $pdo->query($query);
    $bestTime = $stmt->fetch()['best_time'];
    $stats['temps_record'] = $bestTime . " sec";
    
    return $stats;
}

// Récupération des statistiques
$statistics = getStatistics($pdo);

include "partials/header.php"; 

?>
<!-- Déclaration du type de document et de la langue -->
<!DOCTYPE html>
<html lang="fr">
<head>
  <!-- Configuration de l'encodage et de l'affichage sur mobile -->
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>The Power Of Memory</title>

  <!-- Importation des fichiers CSS pour le style -->
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/header.css">
  <link rel="stylesheet" href="assets/css/footer.css">
  <!-- Importation des icônes Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

<!-- Contenu principal de la page -->
<main>
  <!-- Section héro avec titre et bouton d'appel à l'action -->
  <section class="hero">
    <div class="hero-content">
      <h1><br>MEMORY</h1>
      <p>Affrontez votre mémoire !</p>
      <a href="games/memory/level.php" class="btn-primary">JOUER !</a>
    </div>
  </section>

  <!-- Section des fonctionnalités avec images et descriptions -->
  <div class="feature-section">
    <!-- Conteneur des images des thèmes -->
    <div class="images-container">
      <div class="image-item large">
        <img src="https://cdn.mos.cms.futurecdn.net/dNcwyfYm6cLZVJxYuVchnW.jpg" alt="Animals">
      </div>
      <div class="image-item">
        <img src="https://www.macapflag.com/blog/wp-content/uploads/2021/08/Comment-proteger-sa-marque.jpg" alt="Brand">
      </div>
      <div class="image-item">
        <img src="https://www.annonces-automobile.com/images/data/actualite/main/111023.jpg" alt="Cars">
      </div>
    </div>

    <!-- Descriptions des différents thèmes de jeu -->
    <div class="text-container">
      <div class="text-item">
        <div class="number">01</div>
        <h2>Thème Animaux</h2>
        <p>Ce mode vous permet de tester votre mémoire avec des images d'animaux ! </p>
      </div>
      <div class="text-item">
        <div class="number">02</div>
        <h2>Thème Marque</h2>
        <p>Ce mode vous permet de tester votre mémoire avec des images des marques les plus connues ! </p>
      </div>
      <div class="text-item">
        <div class="number">03</div>
        <h2>Thème Voiture</h2>
        <p>Ce mode vous permet de tester votre mémoire avec des images de voiture !</p>
      </div>
    </div>
  </div>

  <section class="stats">
    <div class="game-preview"></div>
    <div class="stats-grid">
        <div class="stat-box purple">
            <h3><?php echo number_format($statistics['parties_jouees']); ?></h3>
            <p>Parties Jouées</p>
        </div>
        <div class="stat-box green">
            <h3><?php echo number_format($statistics['joueurs_connectes']); ?></h3>
            <p>Joueurs Connectés</p>
        </div>
        <div class="stat-box pink">
            <h3><?php echo $statistics['temps_record']; ?></h3>
            <p>Temps Record</p>
        </div>
        <div class="stat-box orange">
            <h3><?php echo number_format($statistics['joueurs_inscrits']); ?></h3>
            <p>Joueurs Inscrits</p>
        </div>
    </div>
</section>
  <!-- Section présentant l'équipe -->
  <section class="team">
    <!-- En-tête de la section équipe avec ornement -->
    <div class="section-header">
      <h2>Notre Équipe</h2>
      <div class="ornament">
        <div class="ornament-line"></div>
        <div class="ornament-symbol">♦</div>
        <div class="ornament-line"></div>
      </div>
    </div>
    <!-- Grille des membres de l'équipe -->
    <div class="team-grid">
      <!-- Cartes individuelles des membres de l'équipe avec leurs réseaux sociaux -->
      <div class="team-member-wrapper">
        <!-- Répété pour chaque membre de l'équipe -->
        <div class="team-member">
          <img src="https://cdn-icons-png.flaticon.com/512/3135/3135768.png" alt="Damien">
          <h3>DAMIEN</h3>
          <p>Game Developer</p>
          <div class="social-links">
            <a href="https://www.facebook.com"><i class="fab fa-facebook-f"></i></a>
            <a href="https://x.com"><i class="fab fa-twitter"></i></a>
            <a href="https://fr.pinterest.com"><i class="fab fa-pinterest"></i></a>
          </div>
        </div>
      </div>
      <div class="team-member-wrapper">
        <div class="team-member">
          <img src="https://cdn-icons-png.flaticon.com/512/3135/3135823.png" alt="Heidi">
          <h3>HEIDI</h3>
          <p>Game Developer</p>
          <div class="social-links">
            <a href="https://www.facebook.com"><i class="fab fa-facebook-f"></i></a>
            <a href="https://x.com"><i class="fab fa-twitter"></i></a>
            <a href="https://fr.pinterest.com"><i class="fab fa-pinterest"></i></a>
          </div>
        </div>
      </div>
      <div class="team-member-wrapper">
        <div class="team-member">
          <img src="https://cdn-icons-png.flaticon.com/512/3135/3135768.png" alt="Léo">
          <h3>LÉO</h3>
          <p>Game Developer</p>
          <div class="social-links">
            <a href="https://www.facebook.com"><i class="fab fa-facebook-f"></i></a>
            <a href="https://x.com"><i class="fab fa-twitter"></i></a>
            <a href="https://fr.pinterest.com"><i class="fab fa-pinterest"></i></a>
          </div>
        </div>
      </div>
      <div class="team-member-wrapper">
        <div class="team-member">
          <img src="https://cdn-icons-png.flaticon.com/512/3135/3135823.png" alt="Sellia">
          <h3>SELLIA</h3>
          <p>Game Developer</p>
          <div class="social-links">
            <a href="https://www.facebook.com"><i class="fab fa-facebook-f"></i></a>
            <a href="https://x.com"><i class="fab fa-twitter"></i></a>
            <a href="https://fr.pinterest.com"><i class="fab fa-pinterest"></i></a>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>

<?php include "partials/footer.php"; ?>

</body>
</html>

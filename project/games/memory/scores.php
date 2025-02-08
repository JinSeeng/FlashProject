<?php
session_start();

// Inclure le fichier database.php pour accéder à la fonction de connexion
require_once('../../utils/database.php');
require_once ('../../utils/common.php');

// Appeler la fonction pour obtenir la connexion PDO
$conn = connectToDbAndGetPdo();

if ($conn === null) {
    exit("Erreur de connexion à la base de données");
}

// Récupérer la requête de recherche, si elle existe
$searchQuery = isset($_GET['searchQuery']) ? $_GET['searchQuery'] : '';

$query = "
    SELECT 
        j.nom as nom_jeu,
        u.pseudo as nom_joueur,
        s.difficulte,
        s.score,
        s.date_partie
    FROM score s
    JOIN jeu j ON s.id_jeu = j.id
    JOIN utilisateur u ON s.id_utilisateur = u.id
";

if (!empty($searchQuery)) {
    $query .= " WHERE u.pseudo LIKE :searchQuery";
}
$query .= " ORDER BY s.date_partie DESC";

// Exécuter la requête SQL
$stmt = $conn->prepare($query);
if (!empty($searchQuery)) {
    $stmt->bindValue(':searchQuery', '%' . $searchQuery . '%', PDO::PARAM_STR);
}
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Déclaration du type de document et de la langue -->
<!DOCTYPE HTML>
<html lang="fr">
<head>
  <!-- Encodage et viewport pour responsive -->
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Scores - The Power Of Memory</title>

  <!-- Inclusion des fichiers CSS -->
  <link rel="stylesheet" href="../../assets/css/style.css">
  <link rel="stylesheet" href="../../assets/css/header.css">
  <link rel="stylesheet" href="../../assets/css/footer.css">
  <link rel="stylesheet" href="../../assets/css/scores.css">
  <!-- Inclusion des icônes Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
<?php include ('../../partials/header.php');
?>

<!-- Contenu principal -->
<main>
  <!-- Section héro avec titre principal -->
  <section class="scores-hero">
    <div class="hero-content">
      <h1>SCORES</h1>
      <p>Découvrez les meilleurs joueurs !</p>
    </div>
  </section>

  <!-- Section du tableau des scores -->
  <section class="scores-section">
    <!-- En-tête de la section avec ornement -->
    <div class="section-header">
    <h2>Tableau des Scores</h2>
    <div class="ornament">
        <div class="ornament-line"></div>
        <div class="ornament-symbol">♦</div>
        <div class="ornament-line"></div>
    </div>
    <div class="search-container">
        <form method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <input type="text" name="searchQuery" id="searchInput" placeholder="Rechercher un joueur" value="<?php echo htmlspecialchars($searchQuery); ?>">
            <button type="submit" id="searchButton">
                <i class="fas fa-search"></i>
            </button>
        </form>
    </div>
    </div>

    <!-- Tableau des scores -->
    <div class="scores-table-container">
      <table class="scores-table">
        <!-- En-tête du tableau -->
        <thead>
        <tr>
          <th>Nom du jeu</th>
          <th>Pseudo</th>
          <th>Difficulté</th>
          <th>Score</th>
          <th>Date et heure</th>
        </tr>
        </thead>
        <!-- Corps du tableau avec les données des scores -->
        <tbody>
        <?php foreach ($result as $row) { ?>
            <tr class="<?php echo isset($_SESSION['pseudo']) && $_SESSION['pseudo'] === $row['nom_joueur'] ? 'highlight' : ''; ?>">
                <td data-label="Nom du jeu"><?php echo htmlspecialchars($row['nom_jeu']); ?></td>
                <td data-label="Pseudo"><?php echo htmlspecialchars($row['nom_joueur']); ?></td>
                <td data-label="Difficulté"><?php echo htmlspecialchars($row['difficulte']); ?></td>
                <td data-label="Score"><?php echo htmlspecialchars($row['score']); ?> sec</td>
                <td data-label="Date et heure"><?php echo htmlspecialchars($row['date_partie']); ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
  </section>
</main>

<?php include ('../../partials/footer.php'); ?>

</body>
</html>
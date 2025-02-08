<?php
// Inclure utils/common.php
require_once __DIR__ . '/../utils/common.php';
?>

<!-- En-tête du site avec le menu -->
<header>
    <nav>
        <!-- Logo du site -->
        <div class="logo">The Power Of Memory</div>
        <!-- Menu de navigation -->
        <div class="nav-links">
            <a href="/index.php">ACCUEIL</a>
            <a href="/games/memory/level.php">JEU</a>
            <a href="/games/memory/scores.php">SCORES</a>
            <a href="/contact.php">CONTACT</a>
            <?php if (isset($_SESSION['user_pseudo'])): ?>
                <a href="/myAccount.php" class="user-pseudo"><?php echo htmlspecialchars($_SESSION['user_pseudo']); ?></a>
            <?php else: ?>
                <a href="/login.php">CONNEXION</a>
            <?php endif; ?>
        </div>
        <!-- Icône du menu burger pour mobile -->
        <div class="mobile-menu-icon">
            <i class="fas fa-bars"></i>
        </div>
    </nav>
</header>

<!-- Script JavaScript pour le menu burger mobile -->
<script>
  // Sélection des éléments du DOM pour le menu
  const mobileMenuIcon = document.querySelector('.mobile-menu-icon');
  const navLinks = document.querySelector('.nav-links');

  // Affichage des éléments lors du clic sur l'icône du menu
  mobileMenuIcon.addEventListener('click', () => {
    navLinks.classList.toggle('show');
  });
</script>

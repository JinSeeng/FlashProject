<?php
require_once 'utils/common.php';

// Détruire toutes les variables de session
$_SESSION = array();

// Détruire la session
session_destroy();

// Rediriger vers la page d'accueil
header('Location: /index.php');
exit();
?>
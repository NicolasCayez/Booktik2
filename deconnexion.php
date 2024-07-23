<?php
// Initialisation de la session
session_start();

// On efface les variables de session
unset($_COOKIE['PHPSESSID']);
$_SESSION = array();

// Destruction de la session.
session_destroy();

// Redirection vers la page de connexion
header("location: index.php");
exit;
?>
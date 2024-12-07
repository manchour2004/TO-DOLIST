<?php
session_start();
session_destroy(); // Détruire toutes les sessions
header('Location: index.html'); // Rediriger vers la page de connexion
exit() ;
?>

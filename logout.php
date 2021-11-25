<?php
// On démarre la session

session_start ();

session_unset ();

session_destroy ();

// On redirige le visiteur vers la page d'accueil
header ('location: login.php');
?>
<?php
    // On démarre la session
    session_start ();

    // On détruit les variables de notre session
    //session_unset ();
    unset($_SESSION['login']);
    unset($_SESSION['pwd']);

    // On détruit notre session
    session_destroy (); //Pour eviter de supprimer le panier lors de la deconnexion
    $_SESSION['panier'] = array();

    // On redirige le visiteur vers la page d'accueil
    header ('location: index.php');
?>
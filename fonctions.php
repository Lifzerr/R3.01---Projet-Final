<?php

function redimage($img_src, $img_dest, $dst_w, $dst_h) {
    // Lit les dimensions de l'image
    $size = GetImageSize("$img_src");
    $src_w = $size[0]; $src_h = $size[1];

    // Teste les dimensions tenant dans la zone
    $test_h = round(($dst_w / $src_w) * $src_h);
    $test_w = round(($dst_h / $src_h) * $src_w);

    // Crée une image vierge aux bonnes dimensions
    $dst_im = ImageCreateTrueColor($dst_w, $dst_h);

    // Copie l'image initiale redimensionnée
    $src_im = ImageCreateFromJpeg("$img_src");

    ImageCopyResampled($dst_im, $src_im, 0, 0, 0, 0, $dst_w, $dst_h, $src_w, $src_h);

    // Sauve la nouvelle image
    ImageJpeg($dst_im, "$img_dest");

    // Détruis les tampons
    ImageDestroy($dst_im);
    ImageDestroy($src_im);

    // Return the path of the resized image
    return $img_dest;
}

function connectionBDLakartxela() {
    // Connexion à la BD
    $servername = "lakartxela.iutbayonne.univ-pau.fr";
    $username = "mbourciez_pro";
    $password = "mbourciez_pro";
    $dbname = "mbourciez_pro";

    $conn = new mysqli($servername, $username, $password, $dbname);
    return $conn;
}

function connectionBDLocalhost() {
    // Connexion à la BD
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "mbourciez_pro";

    $conn = new mysqli($servername, $username, $password, $dbname);
    return $conn;
}

function genererNav() {
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
                <a class="navbar-brand" href="index.php">war.net</a>
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link selected" aria-current="page" href="index.php">Accueil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="panier.php">Panier</a>
                </li>
                <?php
                    if (isset($_SESSION['login']) && isset($_SESSION['pwd'])) {
                        echo '<li class="nav-item">';
                        echo '<a class="nav-link" href="dashboard.php">Dashboard</a>';
                        echo '</li>';
                    }
                ?>
                </ul>
                <form class="d-flex" role="search">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </form>
                <?php 
                    if (isset($_SESSION['login']) && isset($_SESSION['pwd'])) {
                        echo '<a href="logout.php" class="btn btn-primary ms-3" tabindex="-1" role="button" aria-disabled="true">Se déconnecter</a>';
                    }
                    else {
                        echo '<a href="login.html" class="btn btn-primary ms-3" tabindex="-1" role="button" aria-disabled="true">Se connecter</a>';
                    }
                ?>
            </div>
        </div>
    </nav>
}
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
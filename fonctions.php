<?php

function redimage($img_src, $img_dest, $dst_w, $dst_h)
{

    if (!file_exists($img_src)) {
        // Retourner une vignette nulle pour que l'alt s'affiche
        return null;
    }

    // Lit les dimensions de l'image
    $size = GetImageSize("$img_src");
    $src_w = $size[0];
    $src_h = $size[1];
    $mime_type = $size['mime'];

    // Teste les dimensions tenant dans la zone
    $test_h = round(($dst_w / $src_w) * $src_h);
    $test_w = round(($dst_h / $src_h) * $src_w);

    // Crée une image vierge aux bonnes dimensions
    $dst_im = ImageCreateTrueColor($dst_w, $dst_h);

    // Choisit la fonction de création de l'image source en fonction du type MIME
    switch ($mime_type) {
        case 'image/jpeg':
            $src_im = ImageCreateFromJpeg($img_src);
            break;
        case 'image/png':
            $src_im = ImageCreateFromPng($img_src);
            imagecolortransparent($dst_im, imagecolorallocatealpha($dst_im, 0, 0, 0, 127));
            imagealphablending($dst_im, false);
            imagesavealpha($dst_im, true);
            break;
        case 'image/gif':
            $src_im = ImageCreateFromGif($img_src);
            break;
        default:
            throw new Exception("Format d'image non pris en charge : $mime_type");
    }

    // Copie l'image initiale redimensionnée
    ImageCopyResampled($dst_im, $src_im, 0, 0, 0, 0, $dst_w, $dst_h, $src_w, $src_h);

    // Sauvegarde la nouvelle image en fonction du format source
    switch ($mime_type) {
        case 'image/jpeg':
            ImageJpeg($dst_im, $img_dest);
            break;
        case 'image/png':
            ImagePng($dst_im, $img_dest);
            break;
        case 'image/gif':
            ImageGif($dst_im, $img_dest);
            break;
    }

    // Détruis les tampons
    ImageDestroy($dst_im);
    ImageDestroy($src_im);

    // Retourne le chemin de l'image redimensionnée
    return $img_dest;
}

function connectionBD(): mysqli
{
    // Détection de l'environnement
    $serverAvailable = @fsockopen("lakartxela.iutbayonne.univ-pau.fr", 3306, $errno, $errstr, 1); // le @ évite que l'erreur de conexion soit affichée, et permet de connecter aux autres si besoin

    if ($serverAvailable) {
        // Connexion au serveur distant
        $servername = "lakartxela.iutbayonne.univ-pau.fr";
        $username = "mbourciez_pro";
        $password = "mbourciez_pro";
        $dbname = "mbourciez_pro";

        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Échec de la connexion au serveur distant : " . $conn->connect_error);
        }
    } else {
        // Connexion au serveur local (WAMP ou LAMP)
        $servernameLocal = "localhost";
        $usernameLocal = "root";
        $passwordLocalWamp = "";
        $passwordLocalLamp = "root";
        $dbnameLocal = "mbourciez_pro";

        // Tentative de connexion avec le mot de passe WAMP
        $conn = new mysqli($servernameLocal, $usernameLocal, $passwordLocalWamp, $dbnameLocal);
        if ($conn->connect_error) {
            // Tentative de connexion avec le mot de passe LAMP
            $conn = new mysqli($servernameLocal, $usernameLocal, $passwordLocalLamp, $dbnameLocal);
            if ($conn->connect_error) {
                die("Échec de la connexion : " . $conn->connect_error);
            }
        }
    }
    return $conn;
}

function genererNav()
{
    echo '
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
                    </li>';

    if (isset($_SESSION['login']) && isset($_SESSION['pwd'])) {
        echo '
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">Dashboard</a>
                    </li>';
    }
    echo '</ul>';

    if (isset($_SESSION['login']) && isset($_SESSION['pwd'])) {
        echo '
                <a href="logout.php" class="btn btn-primary ms-3" tabindex="-1" role="button" aria-disabled="true">Se déconnecter</a>';
    } else {
        echo '
                <a href="login.php" class="btn btn-primary ms-3" tabindex="-1" role="button" aria-disabled="true">Se connecter</a>';
    }

    echo '
            </div>
        </div>
    </nav>';
}

function genererFooter()
{
    echo '<footer class="bg-dark text-white py-4">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <h5>war.net</h5>
                <p>Le site de vente en ligne de matériel historique de la Seconde Guerre mondiale.</p>
            </div>
            <div class="col-md-4">
                <h5>Liens Utiles</h5>
                <ul class="list-unstyled">
                    <li><a href="index.php" class="text-white">Accueil</a></li>
                    <li><a href="panier.php" class="text-white">Panier</a></li>';
    if (isset($_SESSION['login']) && isset($_SESSION['pwd'])) {
        echo '
                                <li><a href="logout.php" class="text-white">Se déconnecter</a></li>';
    } else {
        echo '
                                <li><a href="login.html" class="text-white">Se connecter</a></li>';
    }
    echo '<li><a href="index.php" class="text-white">Contact</a></li>
                </ul>
            </div>
            <div class="col-md-4">
                <h5>Suivez-nous</h5>
                <a href="#" class="text-white me-2"><i class="fab fa-facebook-f"></i> Facebook</a><br>
                <a href="#" class="text-white me-2"><i class="fab fa-twitter"></i> Twitter</a><br>
                <a href="#" class="text-white"><i class="fab fa-instagram"></i> Instagram</a>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col text-center">
                <p>&copy; 2024 war.net. Tous droits réservés.</p>
            </div>
        </div>
    </div>
    </footer>';
}


// Fonction de recherche de l'index de l'article 
function trouverIndexDesArticles($panier, $article_id)
{
    foreach ($panier as $index => $item) {
        if ($item[0] == $article_id) {
            return $index;
        }
    }
    return false;
}

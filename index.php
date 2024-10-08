<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Site de vente en ligne</title>
    <link rel="stylesheet" href="node_modules\bootstrap\dist\css\bootstrap.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
                <a class="navbar-brand" href="index.php">war.net</a>
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="index.php">Accueil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="panier.php">Panier</a>
                </li>
                <?php
                    session_start();
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

    <div class="container">

        <?php 
            // Connexion à la BD
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "bd-r3.01";

            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Exécuter la requête
            $sql = "SELECT * FROM Article
                LEFT JOIN 
                    Comporter ON Article.id = Comporter.articleId
                LEFT JOIN 
                    Image ON Comporter.imageId = Image.id;";
            $result = $conn->query($sql);

            if ($result->num_rows == 0) {
                echo "pas d'articles disponibles !";
            } 
        ?>

        <div class="row row-cols-3">
            <?php foreach($result as $article) { ?>
            <div class="col-md-4 mb-4">
                <div class="card" style="width: 18rem;">
                    <img src="<?= $article['chemin'] ?>" class="card-img-top" alt="<?= $article['alt'] ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?= $article['titre'] ?></h5>
                        <p class="card-text"><?= $article['description'] ?></p>
                        <a href="#" class="btn btn-primary">Ajouter au panier</a>
                        <a href="#" class="btn btn-secondary">Details</a>
                    </div>
                    <div class="card-footer">
                        Prix : <?= $article['prix'] ?>€
                    </div>
                </div>
            </div>
        </div>

        <?php 
            }
            $conn->close(); 
        ?>
    </div>


    <script src="js/script.js"></script>
</body>
</html>


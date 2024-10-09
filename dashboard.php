<? session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="node_modules\bootstrap\dist\css\bootstrap.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
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
                    <a class="nav-link active" aria-current="page" href="index.php">Accueil</a>
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
                <a href="logout.php" class="btn btn-primary ms-3" tabindex="-1" role="button" aria-disabled="true">Se déconnecter</a>
            </div>
        </div>
    </nav>

    <?php
    if (!(isset($_SESSION['login']) && isset($_SESSION['pwd']))) {
        header('location: login.html');
    }
    ?>

    <div class="container">
        <div id="main" class="card card-body">
        <h2 class="title">Liste des articles</h2>

        <ul id="items" class="list-group">
            <li class="list-group-item">
            <table class="table">
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Description</th>
                    <th>Prix</th>
                    <th>Quantite</th>
                    <th>Chemin</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    // Connexion à la BD
                    $servername = "lakartxela.iutbayonne.univ-pau.fr";
                    $username = "mbourciez_pro";
                    $password = "mbourciez_pro";
                    $dbname = "mbourciez_pro";

                    $conn = new mysqli($servername, $username, $password, $dbname);
                    mysqli_set_charset($conn, "utf8mb4");

                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

                    // Exécuter la requête
                    $sql = "SELECT Article.id, Article.titre, Article.description, Article.prix, Article.quantiteDispo, Image.chemin, Image.alt, Categorie.nom AS categorie
                    FROM Article
                    LEFT JOIN Image ON Article.imageId = Image.id
                    LEFT JOIN Categorie ON Article.categorieId = Categorie.id; ";

                    $result = $conn->query($sql);

                    if (!$result) {
                        die("Erreur lors de l'exécution de la requête : " . $conn->error);
                    }

                    if ($result->num_rows == 0) {
                        echo "pas d'articles disponibles !";
                    } 
                ?>
                <?php foreach($result as $article) { ?>
                <tr>
                    <th scope="row"><?= $article['titre']?></th>
                    <td><?= $article['description']?></td>
                    <td><?= $article['prix']?></td>
                    <td><?= $article['quantiteDispo']?></td>
                    <td><?= $article['chemin']?></td>
                    <td> <button class="btn btn-warning btn-sm float-right btnModifier" data-id="<?= $article['id'] ?>">Modifier</button> </td>
                    <td> <button class="btn btn-danger btn-sm float-right btnSupprimer" data-id="<?= $article['id'] ?>">Supprimer</button> </td>
                </tr>
                <?php 
                    }
                    $conn->close();
                ?>
            </tbody>
            </table>
        </ul>
        </div>
    </div>
    <script src="js/scriptDashboard.js"></script>
</body>
</html>
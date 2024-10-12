<?php 
session_start(); 
require_once('fonctions.php');
?>

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
    <?php genererNav(); ?>

    <?php
    // Rediriger si on est pas identifie
    if (!(isset($_SESSION['login']) && isset($_SESSION['pwd']))) {
        header('location: login.html');
    }
    ?>

    <div class="container mt-5">
        <div id="main" class="card card-body">
        <div class="card-header d-flex justify-content-between">
            <h2 class="title d-md-inline-flex">Liste des articles</h2>
            <button type="button" class="btn btn-primary d-md-inline-flex mb-2" onclick="window.location.href='ajout-materiel.php'">Ajouter un article</button>
        </div>
        

        <ul id="items" class="list-group">
            <li class="list-group-item">
            <table class="table">
            <thead>
                <tr>
                    <th class="d-none">Id</th>
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
                    require_once('fonctions.php');

                    // Connection Bd
                    $conn = connectionBDLocalhost();
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
                <?php 
                    // Display les articles
                    foreach($result as $article) { ?>
                <tr>
                    <th scope="row" class="d-none"><?= $article['id']?></th>
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
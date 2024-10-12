<?php
    require_once('fonctions.php'); 
    session_start();
    ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Site de vente en ligne</title>
    <link rel="stylesheet" href="node_modules\bootstrap\dist\css\bootstrap.css">
</head>
<body>
    <?php genererNav(); ?>

    <div class="container mt-5">
        <div id="main" class="card card-body">
        <div class="card-header d-flex justify-content-between">
            <h2 class="title d-md-inline-flex">Votre Panier</h2>
            <button type="button" class="btn btn-primary d-md-inline-flex mb-2" onclick="window.location.href='paiement.php'">Payer</button>
        </div>
        

        <ul id="items" class="list-group">
            <li class="list-group-item">
            <table class="table">
            <thead>
                <tr>
                    <th class="d-done">Titre</th>
                    <th>Description</th>
                    <th>Prix</th>
                    <th>Quantite</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php 
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
                    <th scope="row" ><?= $article['titre']?></th>
                    <td><?= $article['description']?></td>
                    <td><?= $article['prix']?></td>
                    <td><?= $article['quantiteDispo']?></td>
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


    <script src="js/script.js"></script>
</body>
</html>


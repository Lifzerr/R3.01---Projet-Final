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
    <div class="d-flex flex-column min-vh-100"> <!-- Conteneur principal -->
    <main class="flex-grow-1">
    <div class="container mt-5">
        <div id="main" class="card card-body">
        <div class="card-header d-flex justify-content-between">
            <h2 class="title d-md-inline-flex">Votre Panier</h2>
            <?php 
                if (sizeof($_SESSION['panier']) == 1) {
                    echo "<button type='button' disabled class='btn btn-primary d-md-inline-flex mb-2' onclick=\"window.location.href='paiement.php'\">Payer</button>";
                } else {
                    echo "<button type='button' class='btn btn-primary d-md-inline-flex mb-2' onclick=\"window.location.href='paiement.php'\">Payer</button>";
                }
            ?>

        </div>
        
        <?php 
        if (sizeof($_SESSION['panier']) == 1) {
            echo "Votre panier est vide !";
        } else { ?>

        <ul id="items" class="list-group">
            <li class="list-group-item">
            <table class="table">
            <thead>
                <tr>
                    <th class="d-done">Article</th>
                    <th>Description</th>
                    <th>Prix</th>
                    <th>Quantite</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
    
        <?php
        foreach ($_SESSION['panier'] as $key => $articleInfo) {
            $articleId = $articleInfo[0]; // La premiere valeure est l'ID
            $quantite = $articleInfo[1]; // La seconde est le nombre d'occurrences de l'item

            // Connection à la db
            $conn = connectionBD();
            mysqli_set_charset($conn, "utf8mb4");

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Requete pour recupere les détails de la requete
            $sql = "SELECT Article.id, Article.titre, Article.description, Article.prix, Image.chemin, Image.alt, Categorie.nom AS categorie
                    FROM Article
                    LEFT JOIN Image ON Article.imageId = Image.id
                    LEFT JOIN Categorie ON Article.categorieId = Categorie.id
                    WHERE Article.id = ?;";
            
            //Exec de la resuete parametree
            $requete = $conn->prepare($sql);
            $requete->bind_param("i", $articleId);
            $requete->execute();
            $result = $requete->get_result();
            
            foreach ($result as $article) { ?>
                <tr>
                    <th scope="row" class="d-none"><?= $article['id'] ?></th>
                    <th scope="row"><?= $article['titre'] ?></th>
                    <td><?= $article['description'] ?></td>
                    <td>
                        <?php
                            // Affiche le prix en fonction de la quantite
                            echo $article['prix'] * $quantite;
                        ?>
                    </td>
                    <td>
                        <?php
                            echo $quantite;
                        ?>
                    </td>
                </tr>
            <?php 
            }
            }
    ?>                
    <?php 
    $conn->close();
    }
    ?>
</tbody>
</table>

        </ul>
        </div>
    </div>
</main>
    <?php genererFooter(); ?>
</div>
    <script src="js/script.js"></script>
</body>
</html>


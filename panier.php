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
            // $articleInfo now contains [article_id, boolean]
            $articleId = $articleInfo[0]; // The first value is the article ID
            $quantite = $articleInfo[1]; // The second value is whether it's already displayed

            // Connection to the database
            $conn = connectionBDLocalhost();
            mysqli_set_charset($conn, "utf8mb4");

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // SQL query to fetch article details
            $sql = "SELECT Article.id, Article.titre, Article.description, Article.prix, Image.chemin, Image.alt, Categorie.nom AS categorie
                    FROM Article
                    LEFT JOIN Image ON Article.imageId = Image.id
                    LEFT JOIN Categorie ON Article.categorieId = Categorie.id
                    WHERE Article.id = ?;";
            
            $requete = $conn->prepare($sql);
            $requete->bind_param("i", $articleId);
            $requete->execute();
            $result = $requete->get_result();
            
            // Fetch article details and display it
            $compteur = $_SESSION['panier'][1]; // Counts occurrences of article IDs

            foreach ($result as $article) { ?>
                <tr>
                    <th scope="row" class="d-none"><?= $article['id'] ?></th>
                    <th scope="row"><?= $article['titre'] ?></th>
                    <td><?= $article['description'] ?></td>
                    <td>
                        <?php
                            // Display total price (price * quantity)
                            echo $article['prix'] * $quantite;
                        ?>
                    </td>
                    <td>
                        <?php
                            // Display the quantity
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

    <?php genererFooter(); ?>
    <script src="js/script.js"></script>
</body>
</html>


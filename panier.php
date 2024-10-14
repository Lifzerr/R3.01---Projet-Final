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
    var_dump($_SESSION['panier']);          //A true quand reload de la page car pas de changement de var
    foreach ($_SESSION['panier'] as $key => $articleInfo) {
        // $articleInfo now contains [article_id, boolean]
        $articleId = $articleInfo[0]; // The first value is the article ID
        $isDisplayed = $articleInfo[1]; // The second value is whether it's already displayed

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

        if (!$result) {
            die("Erreur lors de l'exécution de la requête : " . $conn->error);
        }
        if ($result->num_rows == 0) {
            echo "pas d'articles disponibles !";
        } 
        
        // Fetch article details and display it
        $compteur = array_count_values(array_column($_SESSION['panier'], 0)); // Counts occurrences of article IDs

        foreach ($result as $article) { 
            if ($_SESSION['panier'][$key][1] == false) {
                // Set the article as displayed (replace false with true in the session)
                $_SESSION['panier'][$key][1] = true; 

                ?>
                <tr>
                    <th scope="row" class="d-none"><?= $article['id'] ?></th>
                    <th scope="row"><?= $article['titre'] ?></th>
                    <td><?= $article['description'] ?></td>
                    <td>
                        <?php
                            // Display total price (price * quantity)
                            echo $article['prix'] * $compteur[$article['id']];
                        ?>
                    </td>
                    <td>
                        <?php
                            // Display the quantity
                            echo $compteur[$article['id']];
                        ?>
                    </td>
                </tr>
                <?php 
            }
        }
    } 
    ?>                
    <?php 
    $conn->close();
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


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
                    foreach ($_SESSION['panier'] as $key => $value) {
                        // Connection Bd
                        $conn = connectionBDLocalhost();
                        mysqli_set_charset($conn, "utf8mb4");

                        if ($conn->connect_error) {
                            die("Connection failed: " . $conn->connect_error);
                        }

                        // Exécuter la requête
                        $sql = "SELECT Article.id, Article.titre, Article.description, Article.prix, Image.chemin, Image.alt, Categorie.nom AS categorie
                                FROM Article
                                LEFT JOIN Image ON Article.imageId = Image.id
                                LEFT JOIN Categorie ON Article.categorieId = Categorie.id
                                WHERE Article.id = ?;";
                        
                        $requete = $conn->prepare($sql);
                        $requete->bind_param("i", $value);
                        $requete->execute();
                        $result = $requete->get_result();

                        if (!$result) {
                            die("Erreur lors de l'exécution de la requête : " . $conn->error);
                        }
                        if ($result->num_rows == 0) {
                            echo "pas d'articles disponibles !";
                        } 
                    
                    // Display les articles
                    foreach($result as $key => $article) { 
                        $compteur = array_count_values($_SESSION['panier']);
                        $premiereOccurence = array_search($article['id'], $_SESSION['panier']); // Use the article ID as the value to search for
                        if ($compteur[$article['id']] > 1) {
                            // Display the article only once
                            echo $compteur[$article['id']];
                            //$compteur[$article['id']] = array_count_values($_SESSION['panier']);
                            //unset($_SESSION['panier'][$premiereOccurence]);
                        }
                        ?>
                        <tr>
                            <th scope="row" class="d-none"><?= $article['id']?></th>
                            <th scope="row"><?= $article['titre']?></th>
                            <td><?= $article['description']?></td>
                            <td>
                                <?php
                                    echo $article['prix'] * $compteur[$article['id']];
                                ?>
                            </td>
                            <td>
                            <?php
                                // Display the count of the article in the cart
                                if (isset($compteur[$article['id']]) && $compteur[$article['id']] > 1) {
                                    // Display the quantity if greater than 1
                                    echo $compteur[$article['id']];
                                } else {
                                    // Display '1' if the quantity is exactly 1
                                    echo "1";
                                }
                            ?>
                            </td>
                        </tr>
                        <?php 
                    }}
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


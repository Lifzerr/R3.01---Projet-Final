<?php
    session_start();
    require_once('fonctions.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>War.net | Vente de matériel militaire</title>
    <link rel="stylesheet" href="node_modules\bootstrap\dist\css\bootstrap.css">
</head>
<body>
    <?php genererNav(); ?>

    <div class="container">

        <div id="titre" class="mt-5 mb-5">
            <h1 class="h1">Articles de la 2nde Guerre Mondiale</h1>
        </div>

        <?php 
            require_once('fonctions.php');

            $conn = connectionBDLocalhost();
            mysqli_set_charset($conn, "utf8mb4");

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Exécuter la requête
            $sql = "SELECT Article.id, Article.titre, Article.description, Article.prix, Image.chemin, Image.alt, Categorie.nom AS categorie
            FROM Article
            LEFT JOIN Image ON Article.imageId = Image.id
            LEFT JOIN Categorie ON Article.categorieId = Categorie.id;
            ";
            $result = $conn->query($sql);
            if (!$result) {
                die("Erreur lors de l'exécution de la requête : " . $conn->error);
            }

            if ($result->num_rows == 0) {
                echo "pas d'articles disponibles !";
            } 
        ?>

        <div class="row">
            <?php foreach($result as $article) { ?>
            <div class="col-md-4 mb-4">
                <div class="card" style="width: 18rem; min-height: 250px;">
                    <img src="<?=redimage($article['chemin'], 'vignettes/' . $article['titre'], 200, 200);?>" class="card-img-top" alt="<?= $article['alt'] ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?= $article['titre'] ?></h5>
                        <p class="card-text"><?= $article['description'] ?></p>
                        <form method="post" action="index.php">
                            <input type="hidden" name="article_id" value="<?= $article['id'] ?>">
                            <button type="submit" class="btn btn-primary">Ajouter au panier</button>
                        </form>
                        <a href="#" class="btn btn-secondary">Detail</a>
                    </div>
                    <div class="card-footer">
                        Prix : <?= $article['prix'] ?>€
                    </div>
                </div>
            </div>
            <?php 
            }
            $conn->close(); 
        ?>
        </div>

        
    </div>

    <?php
    // Initialize the 'panier' if it's not already set
    if (!isset($_SESSION['panier'])) {
        $_SESSION['panier'] = array();
    }

    // Get the article ID from the POST request
    $article_id = $_POST['article_id'];

    // Function to find if the article exists in the array and return its index
    function find_article_index($panier, $article_id) {
        foreach ($panier as $index => $item) {
            if ($item[0] == $article_id) {
                return $index;
            }
        }
        return false;
    }

    // Check if the article already exists in the array
    $article_index = find_article_index($_SESSION['panier'], $article_id);

    if ($article_index !== false) {
        // If the article already exists, increment the count (assuming the second value holds count)
        $_SESSION['panier'][$article_index][1] += 1;
    } else {
        // If it doesn't exist, add it with a default count of 1
        array_push($_SESSION['panier'], [$article_id, 1]);
    }
    ?>


    <?php genererFooter(); ?>
    <script src="js/script.js"></script>
</body>
</html>


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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php genererNav(); ?>

    <div class="container">

        <div id="titre" class="mt-5 mb-5">
            <h1 class="h1">Articles de la 2nde Guerre Mondiale</h1>
        </div>

        <?php
        require_once('fonctions.php');

        $conn = connectionBD();
        mysqli_set_charset($conn, "utf8mb4");

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Exécuter la requête
        $sql = "SELECT Article.id, Article.titre, Article.description, Article.quantiteDispo, Article.prix, Image.chemin, Image.alt, Categorie.nom AS categorie
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
            <?php foreach ($result as $article) { ?>
                <div class="col-md-4 mb-4">
                    <div class="card" style="width: 18rem; min-height: 250px;">
                        <img src="<?= redimage($article['chemin'], 'vignettes/' . $article['titre'], 200, 200); ?>" class="card-img-top" alt="<?= $article['alt'] ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?= $article['titre'] ?></h5>
                            <p class="card-text"><?= $article['description'] ?></p>
                            <div class="d-flex justify-content-between">
                                <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#fenetreModale-<?= $article['id'] ?>">Details</button>
                                <form method="post" action="index.php" class="d-md-inline-flex">
                                    <!-- <input type="hidden"> -->
                                    <button type="submit" class="btn btn-primary" name="article_id" value="<?= $article['id'] ?>">Ajouter au panier</button>
                                </form>
                            </div>

                        </div>
                        <div class="card-footer">
                            Prix : <?= $article['prix']*1 ?>€
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="fenetreModale-<?= $article['id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg"> <!-- Utilisation d'une modale large -->
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel"><?= htmlspecialchars($article['titre']) ?></h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="text-center"> <!-- Centrer le contenu -->
                                    <img src="<?= htmlspecialchars($article['chemin']) ?>" alt="<?= htmlspecialchars($article['alt']) ?>" class="img-fluid mb-3" style="max-height: 300px;"> <!-- Image responsive -->
                                </div>
                                <p><strong>ID:</strong> <?= htmlspecialchars($article['id']) ?></p>
                                <p><strong>Description:</strong> <?= nl2br(htmlspecialchars($article['description'])) ?></p>
                                <p><strong>Prix:</strong> <?= htmlspecialchars($article['prix']*1) ?> €</p>
                                <p><strong>Quantité disponible:</strong> <?= htmlspecialchars($article['quantiteDispo']) ?></p>
                            </div>
                            <div class="modal-footer">
                                <form method="post" action="index.php">
                                    <button type="submit" class="btn btn-secondary" name="article_id" value="<?= $article['id'] ?>">Ajouter au panier</button>
                                </form>
                                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Fermer</button>
                            </div>
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

    // trouve l'index de l'article dans le panier
    function trouverIndexDesArticles($panier, $article_id)
    {
        foreach ($panier as $index => $item) {
            if ($item[0] == $article_id) {
                return $index;
            }
        }
        return false;
    }

    // Initialise le panier s'il n'existe pas
    if (!isset($_SESSION['panier'])) {
        $_SESSION['panier'] = array();
    }

    // Récupère l'ID de l'article depuis le formulaire (assure que c'est un entier)
    $article_id = isset($_POST['article_id']) ? (int)$_POST['article_id'] : 0;

    // Vérifie que l'ID de l'article est valide
    if ($article_id > 0) {
        // Trouve l'index de l'article dans le panier
        $article_index = trouverIndexDesArticles($_SESSION['panier'], $article_id);

        if ($article_index !== false) {
            // Si l'article existe, on augmente son nombre
            $_SESSION['panier'][$article_index][1] += 1;
        } else {
            $_SESSION['panier'][] = [$article_id, 1];
        }
    } else {
        echo "ID d'article invalide.";
    }
    ?>


    <?php genererFooter(); ?>
    <script src="js/script.js"></script>
    <!-- Bootstrap 4.6.2 JS and dependencies (jQuery and Popper.js) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.6.2/js/bootstrap.min.js"></script>
    <!-- Bootstrap CSS -->
    <!-- Bootstrap JS (with Popper.js) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
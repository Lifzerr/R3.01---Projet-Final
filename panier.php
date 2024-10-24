<?php
    require_once('fonctions.php'); 
    session_start();

    function getMax(int $idArticle){
        $conn = connectionBD();
        mysqli_set_charset($conn, "utf8mb4");

        $sql = "SELECT quantiteDispo FROM Article WHERE id = ?";
        $requete = $conn->prepare($sql);
        $requete->bind_param("i", $idArticle);
        $requete->execute();
        $result = $requete->get_result();
        $nbDispo = $result->fetch_assoc()['quantiteDispo'];

        return $nbDispo;
    }

    // Vérifier si une requête POST a été faite pour vider le panier
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'vider_panier') {
        // Vider le panier
        unset($_SESSION['panier']);

        // Réponse à envoyer au client
        echo "Panier vidé";
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Site de vente en ligne</title>
    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.css">
</head>
<body>
    <?php genererNav(); ?>
    <div class="d-flex flex-column min-vh-100"> <!-- Conteneur principal -->
        <main class="flex-grow-1">
            <div class="container mt-5">
                <div id="main" class="card card-body">
                    <div class="card-header d-flex justify-content-between">
                        <h2 class="title d-md-inline-flex">Votre Panier</h2>
                        <div class="d-flex justify-content-around">
                            <?php 
                            // Désactiver le bouton de paiement si le panier est vide ou non défini
                            if (!isset($_SESSION['panier']) || empty($_SESSION['panier'])) {
                                echo "<button type='button' disabled class='btn btn-danger d-md-inline-flex mb-2 mx-2'>Vider le panier</button>";
                                echo "<button type='button' disabled class='btn btn-primary d-md-inline-flex mb-2 mx-2'>Payer</button>";
                            } else {
                                echo "<button type='button' class='btn btn-danger d-md-inline-flex mb-2 mx-2' onclick=\"viderPanier()\">Vider le panier</button>";
                                echo "<button type='button' class='btn btn-primary d-md-inline-flex mb-2' onclick=\"window.location.href='paiement.php'\">Payer</button>";
                            }
                        ?></div>
                    </div>
                    
                    <?php 
                    // Vérifier si le panier est vide ou non défini
                    if (!isset($_SESSION['panier']) || empty($_SESSION['panier'])) {
                        echo "<p>Votre panier est vide !</p>";
                    } else { ?>
                        <table class="table">
                        <thead>
                            <tr>
                                <th>Article</th>
                                <th>Description</th>
                                <th>Prix</th>
                                <th>Quantité</th>
                            </tr>
                        </thead>
                        <tbody>
                    
                    <?php
                    // Connection à la base de données
                    $conn = connectionBD();
                    mysqli_set_charset($conn, "utf8mb4");

                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

                    // Parcourir les articles du panier
                    foreach ($_SESSION['panier'] as $key => $articleInfo) {
                        $articleId = (int) $articleInfo[0]; // La première valeur est l'ID (en entier)
                        $quantite = (int) $articleInfo[1]; // La seconde est la quantité

                        // Vérifier que l'ID et la quantité sont valides
                        if ($articleId > 0 && $quantite > 0) {
                            // Requête pour récupérer les détails de l'article
                            $sql = "SELECT Article.id, Article.titre, Article.description, Article.prix, Image.chemin, Image.alt
                                    FROM Article
                                    LEFT JOIN Image ON Article.imageId = Image.id
                                    WHERE Article.id = ?;";
                            
                            // Exécuter la requête paramétrée
                            $requete = $conn->prepare($sql);
                            $requete->bind_param("i", $articleId);
                            $requete->execute();
                            $result = $requete->get_result();
                            
                            // Vérifier si un article correspondant a été trouvé
                            if ($result && $result->num_rows > 0) {
                                $article = $result->fetch_assoc(); ?>
                                <tr class="align-items-center">
                                    <th scope="row" class="d-none align-middle"><?= htmlspecialchars($article['id']) ?></th>
                                    <td class="align-middle"><?= htmlspecialchars($article['titre']) ?></td>
                                    <td class="align-middle"><?= htmlspecialchars($article['description']) ?></td>
                                    <td class="align-middle prix">
                                        <?php
                                            // Afficher le prix total en fonction de la quantité
                                            echo htmlspecialchars($article['prix']) * $quantite;
                                        ?>
                                    </td>
                                    <td>
                                    <input type='number' data-article-id='<?= htmlspecialchars($article["id"]) ?>' value='<?= $quantite ?>' min='0' max='<?= getMax($article["id"]) ?>' class='form-control' style='width: 70px;'>
       
                                    </td>
                                </tr>
                                <?php 
                            } else {
                                // Afficher un message si l'article n'est pas trouvé
                                echo "<tr><td colspan='4'>Article introuvable (ID: " . htmlspecialchars($articleId) . ").</td></tr>";
                            }

                            $requete->close();
                        } else {
                            echo "<tr><td colspan='4'>Erreur : ID d'article ou quantité invalide.</td></tr>";
                        }
                    }

                    $conn->close(); // Fermer la connexion après avoir traité tous les articles
                    ?>                
                    </tbody>
                    </table>    
                    <?php } // Fin de la vérification du panier ?>
                
                    <?php
                    $totalPanier = 0; // Initialiser le total du panier
                    $conn = connectionBd();

                    // Vérifier si le panier n'est pas vide
                    if (!empty($_SESSION['panier'])) {
                        foreach ($_SESSION['panier'] as $key => $articleInfo) {
                            $articleId = (int)$articleInfo[0];
                            $quantite = (int)$articleInfo[1];

                            // Requête pour récupérer le prix de l'article
                            $sql = "SELECT prix FROM Article WHERE id = ?";
                            $requete = $conn->prepare($sql);
                            $requete->bind_param("i", $articleId);
                            $requete->execute();
                            $result = $requete->get_result();

                            // Vérifier si l'article a été trouvé
                            if ($result && $result->num_rows > 0) {
                                $article = $result->fetch_assoc();
                                $prixArticle = (float)$article['prix'];

                                // Ajouter le prix total de cet article (quantité * prix) au total du panier
                                $totalPanier += $prixArticle * $quantite;
                            }
                        }
                    }
                    ?>

                    <div id="total-panier-container" style="text-align: right; margin-top: 20px;">
                        <strong>Total du panier : </strong><span id="totalPanier"><?= number_format($totalPanier, 2, ',', ' ') ?></span> €
                    </div>
                </div>
            </div>
        </main>
        <?php genererFooter(); ?>
    </div>
    <script src="js/scriptPanier.js"></script>
</body>
</html>

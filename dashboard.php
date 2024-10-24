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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
                <h2 class="title d-md-inline-flex">Dashboard</h2>
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
                            // Connection Bd
                            $conn = connectionBD();
                            mysqli_set_charset($conn, "utf8mb4");

                            if ($conn->connect_error) {
                                die("Connection failed: " . $conn->connect_error);
                            }

                            // Exécuter la requête
                            $sql = "SELECT Article.id, Article.titre, Article.description, Article.descriptionLongue, Article.prix, Article.quantiteDispo, Image.chemin, Image.alt, Categorie.nom AS categorie
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
                            // Enregistrer les modifications
                            if (
                                isset($_POST['titre']) &&
                                isset($_POST['description']) &&
                                isset($_POST['prix']) &&
                                isset($_POST['quantiteDispo']) &&
                                isset($_POST['categorie']) &&
                                isset($_POST['alt']) //&&
                                //isset($_FILES['image'])
                            ) {
                                $articleId = $_POST['article_id'];
                                $titre = $_POST['titre'];
                                $description = $_POST['description'];
                                $prix = $_POST['prix'];
                                $quantiteDispo = $_POST['quantiteDispo'];
                                $categorie = $_POST['categorie'];
                                $alt = $_POST['alt'];
                                //$image = $_FILES['image'];

                                //Requete principale
                                $sql = "SELECT Article.id, Article.titre, Article.description, Article.quantiteDispo, Article.prix, Article.imageId, Article.categorieId, Image.id, Image.chemin, Image.alt, Categorie.nom, Categorie.id AS categorie
                                FROM Article
                                LEFT JOIN Image ON Article.imageId = Image.id
                                LEFT JOIN Categorie ON Article.categorieId = Categorie.id
                                WHERE Article.id = ?;";
                                $stmtPrinc = $conn->prepare($sql);
                                $stmtPrinc->bind_param("i", $articleId);
                                $stmtPrinc->execute();
                                $result = $stmtPrinc->get_result();

                                if ($row = $result->fetch_assoc()) {
                                    // Récupérer les valeurs des colonnes
                                    $articleTitre = $row['titre'];
                                    $ancienChemin = "images/" . $articleTitre . ".jpg";
                                }

                                //Modifier les parametres de l'article
                                $sql = "UPDATE Article SET titre = ?, description = ?, prix = ?, quantiteDispo = ? WHERE id = ?";
                                $stmt = $conn->prepare($sql);
                                $stmt->bind_param("ssdii", $titre, $description, $prix, $quantiteDispo, $articleId);
                                $result = $stmt->execute();
                                $stmt->close();

                                //Modifier les parametres de la categorie
                                // $sql = "SELECT id FROM Categorie WHERE nom = ?";
                                // $stmt = $conn->prepare($sql);
                                // $stmt->bind_param("s", $categorie);
                                // $resultCat = $stmt->execute();
                                // var_dump($resultCat);
                                // $stmt->close();

                                // //Modifier la categorie de l'article
                                $sql = "UPDATE Article 
                                LEFT JOIN Categorie ON Article.categorieId = Categorie.id
                                SET Article.categorieId = ? WHERE Article.id = ?;";
                                $stmt = $conn->prepare($sql);
                                $stmt->bind_param("si", $categorie, $articleId);
                                $resultat = $stmt->execute();
                                $stmt->close();

                                // Insertion de l'image dans la BD
                                $chemin = "images/";
                                $cheminImage = $chemin . $titre . ".jpg";
                                rename($ancienChemin, $cheminImage);
                                $sql = "UPDATE Image 
                                LEFT JOIN Article ON Article.imageId = Image.id
                                SET chemin = ?, alt = ? WHERE Article.id = ?";
                                $stmt = $conn->prepare($sql);
                                $stmt->bind_param("ssi", $cheminImage, $alt, $articleId);
                                $result = $stmt->execute();
                                $stmt->close();
                                header('location: dashboard.php');
                            }
                            ?>

                            <?php
                            // Display les articles
                            foreach ($result as $article) { ?>
                                <tr>
                                    <th scope="row" class="d-none"><?= $article['id'] ?></th>
                                    <th scope="row"><?= $article['titre'] ?></th>
                                    <td><?= $article['description'] ?></td>
                                    <td><?= $article['prix'] * 1 ?></td>
                                    <td><?= $article['quantiteDispo'] ?></td>
                                    <td><?= $article['chemin'] ?></td>
                                    <td> <button type="button " class="btn btn-warning btn-sm float-right btnModifier" data-bs-toggle="modal" data-bs-target="#fenetreModale-<?= $article['id'] ?>" data-id="<?= $article['id'] ?>">Modifier</button> </td>
                                    <td> <button class="btn btn-danger btn-sm float-right btnSupprimer" data-id="<?= $article['id'] ?>">Supprimer</button> </td>
                                </tr>
                                <div class="modal fade" id="fenetreModale-<?= $article['id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="exampleModalLabel"><?= 'Modification de l\'article ' . htmlspecialchars($article['titre']) ?></h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="dashboard.php" method="POST" enctype="multipart/form-data">
                                                <div class="modal-body">
                                                    <div class="container mt-2">
                                                        <input type="hidden" name="article_id" value="<?= $article['id'] ?>">
                                                        <div class="form-group">
                                                            <label for="titre">Titre</label>
                                                            <input type="text" class="form-control" id="titre" name="titre" value="<?= $article['titre'] ?>" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="description">Description</label>
                                                            <textarea class="form-control" id="description" name="description" rows="3" required><?= $article['description'] ?></textarea>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="description">Description Longue</label>
                                                            <textarea class="form-control" id="description" name="description" rows="5" required><?= $article['descriptionLongue'] ?></textarea>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="prix">Prix (€)</label>
                                                            <input type="number" step="0.01" class="form-control" id="prix" name="prix" value="<?= $article['prix'] * 1 ?>" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="quantiteDispo">Quantité Disponible</label>
                                                            <input type="number" class="form-control" id="quantiteDispo" name="quantiteDispo" value="<?= $article['quantiteDispo'] ?>" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="categorie">Catégorie</label>
                                                            <select class="form-control" id="categorie" name="categorie" required>
                                                                <option value=""> <!-- A MODIFIER PLUS TARD -->
                                                                    <?php
                                                                    echo $article['categorie'];
                                                                    ?>
                                                                </option>
                                                                <?php
                                                                $sql = "SELECT * FROM Categorie;";
                                                                $result = $conn->query($sql);
                                                                if ($result && $result->num_rows > 0) {
                                                                    while ($row = $result->fetch_assoc()) {
                                                                        $selected = ($row['id'] == $article['categorie']) ? 'selected' : '';
                                                                        echo "<option value='" . $row['id'] . "' $selected>" . $row['nom'] . "</option>";
                                                                    }
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="alt">Texte alternatif à l'image</label>
                                                            <input type="text" class="form-control" id="alt" name="alt" value="<?= $article['alt'] ?>" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="image">Uploader une image</label>
                                                            <input type="file" class="form-control-file" id="image" name="image" accept="image/*">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary" name="update_article">Mettre à jour</button>
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
            </ul>
        </div>
    </div>

    <?php genererFooter(); ?>
    <!-- Bootstrap 4.6.2 JS and dependencies (jQuery and Popper.js) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.6.2/js/bootstrap.min.js"></script>
    <!-- Bootstrap CSS -->
    <!-- Bootstrap JS (with Popper.js) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/scriptDashboard.js"></script>
</body>

</html>
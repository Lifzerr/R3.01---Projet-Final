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
                                                                <option value="" disabled>Sélectionnez une catégorie</option>
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

    <?php
    // if (
    //     isset($_POST['titre']) &&
    //     isset($_POST['description']) &&
    //     isset($_POST['prix']) &&
    //     isset($_POST['quantiteDispo']) &&
    //     isset($_POST['categorie']) &&
    //     isset($_POST['alt']) &&
    //     isset($_FILES['image'])
    // ) {

    if (isset($_FILES['image'])) {
        $titre = $_POST['titre'];
        $description = $_POST['description'];
        $prix = $_POST['prix'];
        $quantiteDispo = $_POST['quantiteDispo'];
        $categorie = $_POST['categorie'];
        $alt = $_POST['alt'];
        $image = $_FILES['image'];

        // Récupération de l'id pour insérer l'image
        $sqlIdImage = 'SELECT id FROM Image JOIN Article ON Article.imageId = Image.id WHERE Article.id = <?php echo $article_id ?>';
        $resultat = $conn->query($sqlIdImage);
        if ($resultat) {
            $row = $resultat->fetch_assoc();
            $idImage = $row['id'];
        } else {
            die("Erreur lors de la récupération de l'id de l'image : " . $conn->error);
        }

        $target_dir = "images/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);

        // Vérifier si le fichier est une image réelle
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check !== false) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                echo "L'image " . htmlspecialchars(basename($_FILES["image"]["name"])) . " a été uploadée.";
            } else {
                die("Erreur lors de l'upload de l'image.");
            }
        } else {
            die("Le fichier n'est pas une image.");
        }


        // Insertion de l'image dans la BD
        $chemin = "images/";
        $nomImage = $image['name'];
        $cheminImage = $chemin . $nomImage;

        $sqlImage = "UPDATE Image SET chemin = '$cheminImage', alt = '$alt' WHERE id = <?php echo $idImage ?>";

        if ($conn->query($sqlImage) === TRUE) {
            echo "Image ajoutée avec succès !";
        } else {
            die("Erreur lors de l'ajout de l'image : " . $conn->error);
        }

        // Ajout de l'article dans la BD
        $sql = "UPDATE Article SET
            titre = ?, description = ?, prix = ?, quantiteDispo = ?, categorieId = ?, imageId = ?
            WHERE id = <?php echo $article_id ?>";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdiii", $titre, $description, $prix, $quantiteDispo, $categorie, $idImage);

        $result = $stmt->execute();

        if ($result) {
            echo "Nouvel article ajouté avec succès !";
            header("Location: dashboard.php"); // Redirection vers le dashboard
            exit();
        } else {
            echo "Erreur : " . $conn->error;
        }
        $conn->close();

        // Traitement du fichier
    } elseif (!isset($_POST['titre'])) {
        echo "Tout est casse";
        $conn->close();
    } elseif (!isset($_FILES['image'])) {
        echo "Bruh";
        $conn->close();
    } else {
        echo "Erreur: tous les fichiers sont requis";
        $conn->close();
    }

    ?>

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
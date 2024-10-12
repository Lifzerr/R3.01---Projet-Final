<?php 
    require_once('fonctions.php');

    $conn = connectionBDLocalhost();
    mysqli_set_charset($conn, "utf8mb4");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajout de Matériel</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
</head>
<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
                <a class="navbar-brand" href="index.php">war.net</a>
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="index.php">Accueil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="panier.php">Panier</a>
                </li>
                <?php
                    if (isset($_SESSION['login']) && isset($_SESSION['pwd'])) {
                        echo '<li class="nav-item">';
                        echo '<a class="nav-link selected" href="dashboard.php">Dashboard</a>';
                        echo '</li>';
                    }
                ?>
                </ul>
                <form class="d-flex" role="search">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </form>
                <a href="logout.php" class="btn btn-primary ms-3" tabindex="-1" role="button" aria-disabled="true">Se déconnecter</a>
            </div>
        </div>
    </nav>
    <div class="container mt-5">
        <h2>Ajouter un Matériel</h2>
        <form action="ajout-materiel.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="titre">Titre</label>
                <input type="text" class="form-control" id="titre" name="titre" placeholder="Titre du matériel" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3" placeholder="Description du matériel" required></textarea>
            </div>
            <div class="form-group">
                <label for="prix">Prix (€)</label>
                <input type="number" step="0.01" class="form-control" id="prix" name="prix" placeholder="Prix" required>
            </div>
            <div class="form-group">
                <label for="quantiteDispo">Quantité Disponible</label>
                <input type="number" class="form-control" id="quantiteDispo" name="quantiteDispo" placeholder="Quantité disponible" required>
            </div>
            <div class="form-group">
                <label for="categorie">Catégorie</label>
                <select class="form-control" id="categorie" name="categorie" required>
                    <option value="" disabled selected>Sélectionner une catégorie</option>
                    <!-- Remplir avec les options de catégorie -->
                    <?php
                        $sql = "SELECT * FROM Categorie;";
                        $result = $conn->query($sql);

                        if (!$result) {
                            die("Erreur lors de l'exécution de la requête : " . $conn->error);
                        } else if ($result->num_rows == 0) {
                            echo "<option>pas de catégories disponibles !</option>";
                        } else {
                            // Afficher les options
                            foreach($result as $categorie){
                                echo "<option value='" . $categorie['id'] . "'>" . $categorie['nom'] . "</option>";
                            }
                        }
                    ?>


                </select>
            </div>
            <div class="form-group">
                <label for="chemin">Texte alternatif à l'image</label>
                <input type="text" class="form-control" id="alt" name="alt" placeholder="Alt de l'image" required>
            </div>
            <div class="form-group">
                <label for="image">Uploader une image</label>
                <input type="file" class="form-control-file" id="image" name="image" accept="image/*">
            </div>
            <button type="submit" class="btn btn-primary">Ajouter le matériel</button>
        </form>
    </div>

    <?php
            if (
                isset($_POST['titre']) &&
                isset($_POST['description']) &&
                isset($_POST['prix']) &&
                isset($_POST['quantiteDispo']) &&
                isset($_POST['categorie']) &&
                isset($_POST['alt']) &&
                isset($_FILES['image'])
            ) {
                
                $titre = $_POST['titre'];
                $description = $_POST['description'];
                $prix = $_POST['prix'];
                $quantiteDispo = $_POST['quantiteDispo'];
                $categorie = $_POST['categorie'];
                $alt = $_POST['alt'];
                $image = $_FILES['image'];

                // Récupération de l'id pour insérer l'image
                $sqlIdImage = "SELECT MAX(id) FROM Image";
                $resultat = $conn->query($sqlIdImage);
                if ($resultat) {
                    $row = $resultat->fetch_assoc();
                    $idImage = $row['MAX(id)'] + 1;
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

                $sqlImage = "INSERT INTO Image (id, chemin, alt) VALUES ('$idImage', '$cheminImage', '$alt')";

                if ($conn->query($sqlImage) === TRUE) {
                    echo "Image ajoutée avec succès !";
                } else {
                    die("Erreur lors de l'ajout de l'image : " . $conn->error);
                }

                // Ajout de l'article dans la BD
                $sql = "INSERT INTO Article (titre, description, prix, quantiteDispo, categorieId, imageId) 
                VALUES ('$titre', '$description', '$prix', '$quantiteDispo', '$categorie', '$idImage')";

                // Redirection si tout est terminé
                if ($conn->query($sql) === TRUE) {
                    echo "Nouvel article ajouté avec succès !";
                    header("Location: dashboard.php"); // Redirection vers le dashboard
                    exit(); // Assurez-vous de sortir du script après la redirection
                } else {
                    echo "Erreur : " . $sql . "<br>" . $conn->error;
                }

            } 

    ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc5KAIl0S1EICeIm8g7G1p6hsUdeK0N6B/Xm99LHE" crossorigin="anonymous"></script>
</body>
</html>

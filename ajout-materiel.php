<?php 
    require_once('fonctions.php');
    session_start();

    $conn = connectionBD();
    mysqli_set_charset($conn, "utf8mb4");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if (
        isset($_POST['titre']) &&
        isset($_POST['descriptionCourte']) &&
        isset($_POST['descriptionLongue']) &&
        isset($_POST['prix']) &&
        isset($_POST['quantiteDispo']) &&
        isset($_POST['alt']) &&
        isset($_FILES['image'])
    ) {
        
        $titre = $_POST['titre'];
        $descriptionCourte = $_POST['descriptionCourte'];
        $descriptionLongue = $_POST['descriptionLongue'];
        $prix = $_POST['prix'];
        $quantiteDispo = $_POST['quantiteDispo'];
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
        $sql = "INSERT INTO Article (titre, description, descriptionLongue, prix, quantiteDispo, imageId) 
                VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssdii", $titre, $descriptionCourte, $descriptionLongue, $prix, $quantiteDispo, $idImage);

        if ($stmt->execute()) {
            echo "Nouvel article ajouté avec succès !";
            header("Location: dashboard.php");
            exit();
        } else {
            die("Erreur : " . $stmt->error);
        }
    } 


?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajout de Matériel</title>
    <link rel="stylesheet" href="node_modules\bootstrap\dist\css\bootstrap.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
</head>
<body>
    <?php genererNav(); ?>
    <div class="container mt-5 min-vh-100">
        <h2>Ajouter un Matériel</h2>
        <form action="ajout-materiel.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="titre">Titre</label>
                <input type="text" class="form-control" id="titre" name="titre" placeholder="Titre du matériel" required>
            </div>
            <div class="form-group">
                <label for="description">Description courte</label>
                <textarea class="form-control" id="descriptionCourte" name="descriptionCourte" rows="3" placeholder="Description courte du matériel" required></textarea>
            </div>
            <div class="form-group">
                <label for="description">Description longue</label>
                <textarea class="form-control" id="descriptionLongue" name="descriptionLongue" rows="5" placeholder="Description du matériel" required></textarea>
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
                <label for="chemin">Texte alternatif à l'image</label>
                <input type="text" class="form-control" id="alt" name="alt" placeholder="Alt de l'image" required>
            </div>
            <div class="form-group">
                <label for="image">Uploader une image</label>
                <input type="file" class="form-control-file" id="image" name="image" accept="image/*" required>
            </div>
            <button type="submit" class="btn btn-primary">Ajouter le matériel</button>
        </form>
    </div>

    <?php genererFooter(); ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc5KAIl0S1EICeIm8g7G1p6hsUdeK0N6B/Xm99LHE" crossorigin="anonymous"></script>
</body>
</html>

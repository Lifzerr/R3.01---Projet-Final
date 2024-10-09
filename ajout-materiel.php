<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajout de Matériel</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
</head>
<body>
    <div class="container mt-5">
        <h2>Ajouter un Matériel</h2>
        <form action="ajouter_materiel.php" method="POST" enctype="multipart/form-data">
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
                        require_once('fonctions.php');

                        $conn = connectionBDLocalhost();
                        mysqli_set_charset($conn, "utf8mb4");

                        if ($conn->connect_error) {
                            die("Connection failed: " . $conn->connect_error);
                        }

                        $sql = "SELECT * FROM Categorie;";
                        $result = $conn->query($sql);

                        if (!$result) {
                            die("Erreur lors de l'exécution de la requête : " . $conn->error);
                        } else if ($result->num_rows == 0) {
                            echo "<option>pas de catégories disponibles !</option>";
                        } else {
                            foreach($result as $categorie){
                                echo "<option value='" . $categorie['id'] . "'>" . $categorie['nom'] . "</option>";
                            }
                        }
                    ?>


                </select>
            </div>
            <div class="form-group">
                <label for="chemin">Chemin de l'image</label>
                <input type="text" class="form-control" id="chemin" name="chemin" placeholder="Chemin de l'image" required>
            </div>
            <div class="form-group">
                <label for="image">Uploader une image</label>
                <input type="file" class="form-control-file" id="image" name="image" accept="image/*">
            </div>
            <button type="submit" class="btn btn-primary">Ajouter le matériel</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc5KAIl0S1EICeIm8g7G1p6hsUdeK0N6B/Xm99LHE" crossorigin="anonymous"></script>
</body>
</html>

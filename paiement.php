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
        <form class="row g-3" id="formDePaiement" method="post">
            <div class="col-md-6">
                <label for="inputEmail4" class="form-label">Nom</label>
                <input type="text" class="form-control" id="inputEmail4" placeholder="Ex : Marrot" >
            </div>
            <div class="col-md-6">
                <label for="inputPassword4" class="form-label">Prenom</label>
                <input type="text" class="form-control" id="inputPassword4" placeholder="Ex : Jean" >
            </div>
            <div class="col-12">
                <label for="inputAddress" class="form-label">Adresse</label>
                <input type="text" class="form-control" id="inputAddress" placeholder="Ex : 15 rue de la Liberté" >
            </div>
            <div class="col-md-6">
                <label for="inputCity" class="form-label">Numero de la carte</label>
                <input type="text" class="form-control" id="inputCity" maxlength="16" placeholder="Ex : 1111222233334444" >
            </div>
            <div class="col-md-2">
                <label for="inputZip" class="form-label">Cryptogramme</label>
                <input type="text" class="form-control" id="inputZip" maxlength="3" placeholder="Ex : 999" >
            </div>
            <div class="col-md-2">
                <label for="validity" class="form-label">Date de validité</label>
                <input type="date" class="form-control" id="validity" >
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary">Enregistrer le paiement</button>
            </div>
        </form>
    </div>

    <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['validity'])) {
            // Get the date from the form input
            $validityDate = $_POST['validity'];

            // Convert the input date to a DateTime object
            $validityDate = new DateTime($validityDate);

            // Get today's date
            $today = new DateTime();

            // Add 3 months to today's date
            $todayPlus3Months = (clone $today)->modify('+3 months');

            // Check if the validity date is greater than today plus 3 months
            if ($validityDate > $todayPlus3Months) {
                echo "La date est supérieure à 3 mois.";
            } else {
                echo "La date n'est pas supérieure à 3 mois.";
            }
        }
    ?>

    <?php genererFooter(); ?>
</body>
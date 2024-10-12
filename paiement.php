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
        <form class="row g-3">
            <div class="col-md-6">
                <label for="inputEmail4" class="form-label">Nom</label>
                <input type="text" class="form-control" id="inputEmail4">
            </div>
            <div class="col-md-6">
                <label for="inputPassword4" class="form-label">Prenom</label>
                <input type="text" class="form-control" id="inputPassword4">
            </div>
            <div class="col-12">
                <label for="inputAddress" class="form-label">Adresse</label>
                <input type="text" class="form-control" id="inputAddress" placeholder="1234 Main St">
            </div>
            <div class="col-md-6">
                <label for="inputCity" class="form-label">Numero de la carte</label>
                <input type="text" class="form-control" id="inputCity" maxlength="16">
            </div>
            <div class="col-md-2">
                <label for="inputZip" class="form-label">Cryptogramme</label>
                <input type="text" class="form-control" id="inputZip" maxlength="3">
            </div>
            <div class="col-md-2">
                <label for="validity" class="form-label">Date de validite</label>
                <input type="date" class="form-control" id="validity">
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary">Enregistrer le paiement</button>
            </div>
        </form>
    </div>
</body>
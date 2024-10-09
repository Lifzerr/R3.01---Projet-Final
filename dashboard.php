<? session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="node_modules\bootstrap\dist\css\bootstrap.css">
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
                <!-- <li class="nav-item">
                    <a class="nav-link disabled" aria-disabled="true">Disabled</a>
                </li> -->
                </ul>
                <form class="d-flex" role="search">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </form>
                <a href="logout.php" class="btn btn-primary ms-3" tabindex="-1" role="button" aria-disabled="true">Se déconnecter</a>
            </div>
        </div>
    </nav>

    <?php
    if (!(isset($_SESSION['login']) && isset($_SESSION['pwd']))) {
        header('location: login.html');
    }

    ?>

    <div class="container">
        <div id="main" class="card card-body">
        <h2 class="title">Liste des articles</h2>

        <ul id="items" class="list-group">
            <li class="list-group-item">
            <table class="table">
            <tbody>
                <tr>
                <th scope="row">Voiture</th>
                <td>Voiture ayant appartenu a Adolf Hitler</td>
                <td>100 000€</td>
                <td>1</td>
                <td>/imges/voitureAdolf.jpg</td>
                <td> <button id="btnModifier" class="btn btn-warning btn-sm float-right">Modifier</button> </td>
                <td> <button id="btnSupprimer" class="btn btn-danger btn-sm float-right">Supprimer</button> </td>
                </tr>
            </tbody>
            </table>
        </ul>
        </div>
    </div>

</body>
</html>
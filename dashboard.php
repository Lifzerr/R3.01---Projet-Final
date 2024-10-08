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
                    <a class="nav-link" href="#">Panier</a>
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
    session_start();

    if (!(isset($_SESSION['login']) && isset($_SESSION['pwd']))) {
        header('location: login.html');
    }

    ?>

    <div class="container">
        <table class="table">
        <thead>
            <tr>
            <th scope="col">#</th>
            <th scope="col">Article</th>
            <th scope="col">Qte Stock</th>
            <th scope="col">Prix HT</th>
            </tr>
        </thead>
        <tbody>
            <tr>
            <th scope="row">Item 1</th>
            <td>Pains au lait (paquet de 10)</td>
            <td>10</td>
            <td>0.95€</td>
            </tr>
            <tr>
            <th scope="row">Item 2</th>
            <td>Miel d'oranger (pot de 250 g)</td>
            <td>29</td>
            <td>4.50€</td>
            </tr>
            <tr>
            <th scope="row">Item 3</th>
            <td>Farine de froment (paquet de 1 kg)</td>
            <td>12</td>
            <td>0.65€</td>
            </tr>
        </tbody>
        </table>
    </div>

    <div class="container">
        <div id="main" class="card card-body">
        <h2 class="title">Ajouter un article à ma sélection ...</h2>
        <form name="monFormulaire" id="addForm" class="form-inline mb-3">
            <input type="text" class="form-control mr-2" id="item" name="ZoneSaisie" />
            <input type="submit" class="btn btn-dark" value="Submit" />
        </form>
        <h4 class="title">Items déjà sélectionnés :</h4>
        <ul id="items" class="list-group font-weight-bold">
            <li class="list-group-item">
            Item 1
            <button class="btn btn-danger btn-sm float-right">X</button>
            </li>
            <li class="list-group-item">
            Item 2
            <button class="btn btn-danger btn-sm float-right">X</button>
            </li>
        </ul>
        </div>
    </div>

</body>
</html>
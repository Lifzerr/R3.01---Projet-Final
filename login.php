<?php 
    session_start(); // Démarre une fois la session
    require_once('fonctions.php'); 

    // On définit un login et un mot de passe de base
    $login_valide = "root";
    $pwd_valide = "root";

    // On teste si le formulaire de connexion a été soumis
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // on vérifie les informations saisies
        if ($login_valide === $_POST['login'] && $pwd_valide === $_POST['pwd']) {
            $_SESSION['login'] = $_POST['login'];
            $_SESSION['pwd'] = $_POST['pwd'];
            header('location: dashboard.php');
            exit(); 
        } else {
            // Message d'alerte si l'utilisateur n'est pas reconnu
            $_SESSION['wrong_login'] = "Utilisateur non reconnu";
            // Redirection sans recharger la page
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }
    }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connectez-vous !</title>
    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.css">
    <script>
         window.onload = function() {
            <?php if (isset($_SESSION['wrong_login'])): ?>
                alert("<?php echo $_SESSION['wrong_login']; ?>");
                <?php unset($_SESSION['wrong_login']); ?> // Supprimer le message après l'affichage
            <?php endif; ?>
        }
    </script>
</head>
<body>
   <?php genererNav() ?>
    
    <div class="container min-vh-100">
        <div class="row justify-content-center">
            <div class="col-md-4 mt-5">
                <form action="login.php" method="post">
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Identifiant</label>
                        <input type="text" name="login" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" required>
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label">Mot de passe</label>
                        <input type="password" name="pwd" class="form-control" id="exampleInputPassword1" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Se connecter</button>
                </form>
            </div>
        </div>
    </div>

    <?php genererFooter(); ?>
    <script src="js/script.js"></script>
</body>
</html>

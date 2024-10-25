<?php
    require_once('fonctions.php');
    session_start();


    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['validity']) && isset($_POST['cardNumber'])) {

        $numCarte = $_POST['cardNumber'];
        if(!($numCarte[0] == $numCarte[strlen($numCarte) - 1]) && $numCarte){
            $_SESSION['card_error'] = "Le premier et le dernier chiffre du numéro de carte doivent être identiques.";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }
    
        $inputDate = $_POST['validity'];
        $inputParts = explode('/', $inputDate);
    
        if (count($inputParts) !== 2 || strlen($inputParts[1]) !== 2) {
            $_SESSION['error'] = "Format de date invalide. Utilisez mm/aa.";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }
    
        // Ajouter '20' devant l'année pour obtenir une année complète (ex: '24' devient '2024')
        $month = $inputParts[0];
        $year = '20' . $inputParts[1];
    
        $inputDateTime = DateTime::createFromFormat('Y-m', $year . '-' . $month);
    
        if ($inputDateTime === false) {
            $_SESSION['error'] = "Format de date invalide. Utilisez mm/aa.";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }
    
        $currentDate = new DateTime();
        $threeMonthsLater = clone $currentDate;
        $threeMonthsLater->modify('+3 months');
    
        // Comparer uniquement le mois et l'année, ignorer le jour
        if ($inputDateTime > $threeMonthsLater) {
            majStocks();
            header("Location: index.php");
            exit();
        } else {
            $_SESSION['date_error'] = "La date d'expiration doit être ultérieure à la date actuelle de 3 mois.";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }
    }

    function majStocks()
    {
        if (empty($_SESSION['panier'])) {
            throw new Exception("Le panier est vide.");
        }
        $conn = connectionBD();
        mysqli_set_charset($conn, "utf8mb4");
        if ($conn->connect_error) {
            throw new Exception("Erreur de connexion à la base de données : " . $conn->connect_error);
        }
        try {
            $conn->begin_transaction(); // Démarrer une transaction pour assurer que toutes les mises à jour se fassent ou aucune.
            foreach ($_SESSION['panier'] as $article) {
                $articleId = isset($article[0]) ? (int)$article[0] : 0;
                $quantite = isset($article[1]) ? (int)$article[1] : 0;
                // Vérifie que l'ID de l'article et la quantité sont valides
                if ($articleId > 0 && $quantite > 0) {
                    $sql = "UPDATE Article SET quantiteDispo = GREATEST(0, quantiteDispo - ?) WHERE id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("ii", $quantite, $articleId);
                    if (!$stmt->execute()) {
                        throw new Exception("Erreur lors de la mise à jour du stock pour l'article ID " . $articleId);
                    }
                    $stmt->close(); // Fermer le statement après chaque exécution
                } else {
                    throw new Exception("Le format du panier est invalide pour l'article avec ID " . htmlspecialchars($articleId));
                }
        
        
            }
            $conn->commit(); // Valider la transaction si tout se passe bien
            // Vider le panier après la mise à jour des stocks
            unset($_SESSION['panier']);
        } catch (Exception $e) {
            $conn->rollback(); // Annuler la transaction en cas d'erreur
            throw $e; // Relancer l'exception pour la gérer ailleurs si nécessaire
        } finally {
            $conn->close(); // Fermer la connexion
        }
    }
    
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Site de vente en ligne</title>
    <link rel="stylesheet" href="node_modules\bootstrap\dist\css\bootstrap.css">
    <script>
        window.onload = function() {
            <?php if (isset($_SESSION['date_error'])): ?>
                alert("<?php echo $_SESSION['date_error']; ?>");
                <?php unset($_SESSION['date_error']); ?> // Supprimer le message après l'affichage
            <?php endif; ?>
            <?php if (isset($_SESSION['card_error'])): ?>
                alert("<?php echo $_SESSION['card_error']; ?>");
                <?php unset($_SESSION['card_error']); ?> // Supprimer le message après l'affichage
            <?php endif; ?>
        }
    </script>
</head>

<body>
    <?php genererNav(); ?>  

    <div class="d-flex flex-column min-vh-100"> <!-- Conteneur principal -->
        <main class="flex-grow-1">
            <div class="container mt-5">
                <form class="row g-3" id="formDePaiement" method="post" action="paiement.php">
                    <div class="col-md-6">
                        <label for="inputEmail4" class="form-label">Nom</label>
                        <input type="text" class="form-control" id="inputEmail4" placeholder="Ex : Marrot" required>
                    </div>
                    <div class="col-md-6">
                        <label for="inputPassword4" class="form-label">Prenom</label>
                        <input type="text" class="form-control" id="inputPassword4" placeholder="Ex : Jean" required>
                    </div>
                    <div class="col-12">
                        <label for="inputAddress" class="form-label">Adresse</label>
                        <input type="text" class="form-control" id="inputAddress" placeholder="Ex : 15 rue de la Liberté" required>
                    </div>
                    <div class="col-md-6">
                        <label for="inputCity" class="form-label">Numero de la carte</label>
                        <input type="text" class="form-control" id="inputCity" maxlength="16" placeholder="Ex : 1111222233334441" name="cardNumber" required>
                    </div>
                    <div class="col-md-2">
                        <label for="inputZip" class="form-label">Cryptogramme</label>
                        <input type="text" class="form-control" id="inputZip" maxlength="3" placeholder="Ex : 999" required>
                    </div>
                    <div class="col-md-2">
                        <label for="validity" class="form-label">Date de validité</label>
                        <input type="month" class="form-control" name="validity" id="validity" pattern="(0[1-9]|1[0-2])/\d{2}" placeholder="Ex : 01/26" required>
                    </div>
                    <div class="col-12">
                        <button type="submit" maxlength="5" class="btn btn-primary">Enregistrer le paiement</button>
                    </div>
                </form>
            </div>
        </main>
        <?php genererFooter(); ?>
    </div>
</body>
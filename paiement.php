<?php
    require_once('fonctions.php');
    session_start();

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['validity'])) {
        $inputDate = $_POST['validity'];
        $inputDateTime = DateTime::createFromFormat('m/y', $inputDate);
        
        if ($inputDateTime === false) {
            $_SESSION['error'] = "Format de date invalide. Utilisez mm/aa.";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }
        
        $currentDate = new DateTime();
        $threeMonthsLater = clone $currentDate;
        $threeMonthsLater->modify('+3 months');
        
        if ($inputDateTime > $threeMonthsLater) {
            try {
                majStocks();
                $_SESSION['success'] = "Commande traitée avec succès. Le panier a été vidé et les stocks ont été mis à jour.";
            } catch (Exception $e) {
                $_SESSION['error'] = "Une erreur est survenue lors du traitement de la commande : " . $e->getMessage();
            }
            header("Location: index.php");
            exit();
        } else {
            $_SESSION['error'] = "La date de validité doit être supérieure à 3 mois à partir d'aujourd'hui.";
            exit();
        }
    }

    function majStocks() {
        if (empty($_SESSION['panier'])) {
            throw new Exception("Le panier est vide.");
        }

        $conn = connectionBD();
        mysqli_set_charset($conn, "utf8mb4");
        
        if ($conn->connect_error) {
            throw new Exception("Erreur de connexion à la base de données : " . $conn->connect_error);
        }
        
        try {
            $conn->begin_transaction();
            
            foreach ($_SESSION['panier'] as $articleId => $quantite) {
                $sql = "UPDATE Article SET stock = GREATEST(0, stock - ?) WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ii", $quantite, $articleId);
                
                if (!$stmt->execute()) {
                    throw new Exception("Erreur lors de la mise à jour du stock pour l'article ID " . $articleId);
                }
                
                $stmt->close();
            }
            
            $conn->commit();
            unset($_SESSION['panier']);
        } catch (Exception $e) {
            $conn->rollback();
            throw $e;
        } finally {
            $conn->close();
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
</head>

<body>
    <?php genererNav(); ?>  

    <div class="d-flex flex-column min-vh-100"> <!-- Conteneur principal -->
        <main class="flex-grow-1">
            <div class="container mt-5">
                <form class="row g-3" id="formDePaiement" method="post" action="paiement.php">
                    <div class="col-md-6">
                        <label for="inputEmail4" class="form-label">Nom</label>
                        <input type="text" class="form-control" id="inputEmail4" placeholder="Ex : Marrot">
                    </div>
                    <div class="col-md-6">
                        <label for="inputPassword4" class="form-label">Prenom</label>
                        <input type="text" class="form-control" id="inputPassword4" placeholder="Ex : Jean">
                    </div>
                    <div class="col-12">
                        <label for="inputAddress" class="form-label">Adresse</label>
                        <input type="text" class="form-control" id="inputAddress" placeholder="Ex : 15 rue de la Liberté">
                    </div>
                    <div class="col-md-6">
                        <label for="inputCity" class="form-label">Numero de la carte</label>
                        <input type="text" class="form-control" id="inputCity" maxlength="16" placeholder="Ex : 1111222233334444">
                    </div>
                    <div class="col-md-2">
                        <label for="inputZip" class="form-label">Cryptogramme</label>
                        <input type="text" class="form-control" id="inputZip" maxlength="3" placeholder="Ex : 999">
                    </div>
                    <div class="col-md-2">
                        <label for="validity" class="form-label">Date de validité</label>
                        <input type="month" class="form-control" name="validity" id="validity" pattern="(0[1-9]|1[0-2])/\d{2}" placeholder="Ex : 01/26">
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
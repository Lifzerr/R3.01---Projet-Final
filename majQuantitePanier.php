<?php
session_start();
require_once('fonctions.php');

// Définir le type de la réponse comme JSON
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['articleId']) && isset($_POST['newQuantity'])) {
    $articleId = (int)$_POST['articleId'];
    $newQuantity = (int)$_POST['newQuantity'];

    $conn = connectionBD();
    mysqli_set_charset($conn, "utf8mb4");

    // Vérifier que l'article existe et récupérer la quantité disponible en stock
    $sql = "SELECT quantiteDispo FROM Article WHERE id = ?";
    $requete = $conn->prepare($sql);
    $requete->bind_param("i", $articleId);
    $requete->execute();
    $result = $requete->get_result();

    // Récupérer l'index de l'article
    $indexArticle = trouverIndexDesArticles($_SESSION['panier'], $articleId);

    if ($result && $result->num_rows > 0) {
        $nbDispo = $result->fetch_assoc()['quantiteDispo'];

        // Vérifier que la nouvelle quantité est valide       
        if ($newQuantity >= 0 && $newQuantity <= $nbDispo) {
            // Mettre à jour la quantité dans la variable de session
            if (isset($_SESSION['panier'][$indexArticle])) {
                // Vérifier la quantité
                if ($newQuantity == 0) {
                    // Supprimer l'article du panier si la quantité est nulle
                    unset($_SESSION['panier'][$indexArticle]);
                    $_SESSION['panier'] = array_values($_SESSION['panier']);
                } else {
                    // Mettre à jour la quantité
                    $_SESSION['panier'][$indexArticle][1] = $newQuantity;
                }

                echo json_encode(['success' => true, 'message' => "Quantité mise à jour avec succès."]);
            } else {
                echo json_encode(['success' => false, 'message' => "Erreur : article non trouvé dans le panier."]);
            }
        } else {
            echo json_encode(['success' => false, 'message' => "Quantité invalide ou stock insuffisant."]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => "Erreur : article non trouvé dans la base de données."]);
    }

    $requete->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => "Requête invalide."]);
}


<?php
// Démarrer la session si vous devez vérifier l'authentification
session_start();
require_once('fonctions.php');

$conn = connectionBDLocalhost();
mysqli_set_charset($conn, "utf8mb4");

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Récupérer les données JSON envoyées par le client
$data = json_decode(file_get_contents('php://input'), true);
$articleId = $data['id'];

// Vérifier que l'ID de l'article est fourni
if (isset($articleId)) {
    // Récupérer le chemin de l'image pour suppression physique
    $sqlTakeImage = "SELECT I.chemin FROM Image I
                    JOIN Article A ON A.imageId = I.id
                    WHERE A.id = ?";
    $stmt = $conn->prepare($sqlTakeImage);
    $stmt->bind_param("i", $articleId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $cheminImage = $row['chemin'];

        // Supprimer l'image du dossier
        if (file_exists($cheminImage)) {
            unlink($cheminImage);
        }
    }
    /*
    // Suppression de l'image
    $sql = "DELETE FROM Image
    JOIN Article A ON A.imageId = I.id
    WHERE A.id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $articleId);
    $resultSuppImage = $stmt->execute();
    */
    // Suppression de l'article
    $sql = "DELETE FROM Article WHERE id = ?;";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $articleId);
    $resultSuppArticle = $stmt->execute();

    

    // Préparer la réponse
    $response = [];

    if ($resultSuppArticle && $resultSuppImage) {
        $response['success'] = true;
        $response['message'] = 'Article supprimé avec succès.';
    } else {
        $response['success'] = false;
        $response['message'] = 'Erreur lors de la suppression : ' . $conn->error;
    }
    
    // Fermer la déclaration
    $stmt->close();
} else {
    $response['success'] = false;
    $response['message'] = 'ID d\'article non fourni.';
}

// Retourner la réponse au format JSON
header('Content-Type: application/json');
echo json_encode($response);

// Fermer la connexion
$conn->close();
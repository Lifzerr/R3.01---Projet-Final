<?php
// Démarrer la session si vous devez vérifier l'authentification
session_start();
require_once('fonctions.php');

$conn = connexionBDLocalhost();
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
    // Préparer et exécuter la requête de suppression
    $sql = "DELETE FROM Article WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $articleId);
    $result = $stmt->execute();

    // Préparer la réponse
    $response = [];

    if ($result) {
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
?>

<?php
require_once('fonctions.php');
session_start();

$conn = connectionBD();
mysqli_set_charset($conn, "utf8mb4");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Récupérer le terme de recherche de l'utilisateur
$termeRecherche = isset($_GET['query']) ? htmlspecialchars(trim($_GET['query'])) : '';

// Préparer la requête pour rechercher les articles
$sql = "SELECT Article.id, Article.titre, Article.description, Article.descriptionLongue, Article.quantiteDispo, Article.prix, Image.chemin, Image.alt, Categorie.nom AS categorie
        FROM Article
        LEFT JOIN Image ON Article.imageId = Image.id
        LEFT JOIN Categorie ON Article.categorieId = Categorie.id
        WHERE Article.titre LIKE ? OR Article.description LIKE ? OR Article.descriptionLongue LIKE ?";

$stmt = $conn->prepare($sql);
$param = "%" . $termeRecherche . "%";
$stmt->bind_param("sss", $param, $param, $param);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "<p>Aucun article trouvé pour le terme '" . $termeRecherche . "'.</p>";
} else {
    echo '<div class="row">';
    foreach ($result as $article) {
        echo '<div class="col-md-4 mb-4">';
        echo '<div class="card text-decoration-none" style="width: 18rem; min-height: 250px; height: 100%; display: flex; flex-direction: column;">';
        echo '<a>';
        echo '<img src="' . htmlspecialchars($article['chemin']) . '" alt="' . htmlspecialchars($article['alt']) . '" class="card-img-top p-2 rounded-top">';
        echo '</a>';
        echo '<div class="card-body d-flex flex-column justify-content-between" style="flex-grow: 1;">';
        echo '<h5 class="card-title">' . htmlspecialchars($article['titre']) . '</h5>';
        echo '<p class="card-text">' . htmlspecialchars($article['description']) . '</p>';
        echo '<div class="d-flex justify-content-between ">';
        echo '<button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#fenetreModale-' . $article['id'] . '">Details</button>';
        echo '</div>';
        echo '</div>';
        echo '<div class="card-footer">Prix : ' . ($article['prix'] * 1) . '€</div>';
        echo '</div>';
        echo '</div>';
    }
    echo '</div>';
}

$stmt->close();
$conn->close();

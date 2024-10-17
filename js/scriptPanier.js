function viderPanier() {
    if (confirm('Êtes-vous sûr de vouloir vider le panier ?')) {
        fetch(window.location.href, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'action=vider_panier'
        })
        .then(response => response.text())
        .then(data => {
            // Afficher un message ou rediriger après le succès
            alert('Panier vidé avec succès.');
            location.reload(); // Rafraîchir la page pour refléter le changement
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Une erreur est survenue lors de la suppression du panier.');
        });
    }
}


// Focntion de gestion du nombre d'articles dans le panier
document.querySelectorAll("input[type='number']").forEach(input => {
    input.addEventListener('change', function() {
        const articleId = this.dataset.articleId;
        const newQuantity = this.value;

        // Créer un objet FormData pour envoyer les données via AJAX
        var formData = new FormData();
        formData.append('articleId', articleId);
        formData.append('newQuantity', newQuantity);

        // Effectuer une requête AJAX pour envoyer les données au serveur
        fetch('majQuantitePanier.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Mettre à jour le prix total et autres informations si nécessaire
                console.log("Quantité mise à jour avec succès !");
                location.reload();
            } else {
                alert(data.message);
                // Recharger la page pour restaurer la valeur d'origine en cas d'erreur
                location.reload();
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Une erreur est survenue lors de la mise à jour de la quantité.');
            location.reload(); // Recharger en cas d'erreur
        });
    });
});

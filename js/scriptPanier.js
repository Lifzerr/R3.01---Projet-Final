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

                
                // Trouver la ligne de l'article et le champ du prix correspondant
                const row = this.closest('tr');
                const priceCell = row.querySelector('.prix').textContent;

                // Vérifier que la nouvelle quantité n'est pas nulle
                if (newQuantity == 0) {
                    // Supprimer la ligne de l'article
                    row.remove();
                }
                else{
                    // Calculer le nouveau prix total pour cet article
                    const newTotalPrice = priceCell * newQuantity;

                    // Mettre à jour l'affichage du prix total
                    priceCell.textContent = newTotalPrice.toFixed(2);
                }
                location.reload();

                
            }
            else{
                alert(data.message);
                // Recharger la page pour restaurer la valeur d'origine
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


// Script vidage de panier
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

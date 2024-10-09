
// Sélectionner tous les boutons "Supprimer"
const btnSupprimer = document.querySelectorAll('.btnSupprimer');

// Boucle sur chaque bouton pour ajouter un gestionnaire d'événements
btnSupprimer.forEach(button => {
    button.addEventListener('click', function() {
        // Récupérer l'ID de l'article à partir de l'attribut data-id
        const articleId = this.getAttribute('data-id');

        // Demander une confirmation à l'utilisateur avant de supprimer
        if (confirm('Êtes-vous sûr de vouloir supprimer cet article ?')) {
            // Envoyer une requête AJAX pour supprimer l'article
            fetch('suppressionDashboard.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ id: articleId }), // Passer l'ID de l'article en JSON
            })
            .then(response => {
                if (response.ok) {
                    return response.json(); // Convertir la réponse en JSON
                }
                throw new Error('Erreur lors de la suppression de l\'article.');
            })
            .then(data => {
                if (data.success) {
                    // Supprimer la ligne correspondante de la table
                    const row = button.closest('tr'); // Trouver la ligne contenant le bouton
                    row.remove(); // Supprimer la ligne de la table
                    alert('Article supprimé avec succès !'); // Alerter l'utilisateur
                } else {
                    alert('Erreur lors de la suppression : ' + data.message);
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Une erreur est survenue. Veuillez réessayer.');
            });
        }
    });
});


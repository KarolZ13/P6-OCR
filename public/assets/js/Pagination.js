document.addEventListener("DOMContentLoaded", function() {
    // Nombre de commentaires à afficher par page
    const commentsPerPage = 10;

    // Sélection des éléments HTML
    const commentsContainer = document.querySelector('.comments-container');
    const prevPageButton = document.getElementById('prevPage');
    const nextPageButton = document.getElementById('nextPage');

    // Récupération de tous les commentaires
    const comments = Array.from(commentsContainer.querySelectorAll('.comment'));

    // Calcul du nombre total de pages
    const totalPages = Math.ceil(comments.length / commentsPerPage);

    // Variable pour suivre la page actuelle
    let currentPage = 1;

    // Fonction pour afficher les commentaires d'une page spécifique
    function showComments(page) {
        const startIndex = (page - 1) * commentsPerPage;
        const endIndex = startIndex + commentsPerPage;

        comments.forEach((comment, index) => {
            comment.style.display = (index >= startIndex && index < endIndex) ? 'block' : 'none';
        });

        // Affichage/masquage des boutons "Page suivante" et "Page précédente"
        nextPageButton.style.display = (currentPage < totalPages) ? 'block' : 'none';
        prevPageButton.style.display = (currentPage > 1) ? 'block' : 'none';
    }

    // Initialisation : Afficher la première page
    showComments(currentPage);

    // Gestion du clic sur le bouton "Page suivante"
    nextPageButton.addEventListener('click', function() {
        currentPage += 1;
        showComments(currentPage);
    });

    // Gestion du clic sur le bouton "Page précédente"
    prevPageButton.addEventListener('click', function() {
        currentPage = Math.max(currentPage - 1, 1);
        showComments(currentPage);
    });
});

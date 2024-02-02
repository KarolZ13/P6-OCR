document.addEventListener('DOMContentLoaded', function() {
    var showMediaButton = document.getElementById('show-media');

    showMediaButton.addEventListener('click', function(event) {
        event.preventDefault(); // Empêche le comportement par défaut de soumission du formulaire

        // Ajoutez votre logique pour afficher le contenu multimédia ici
        // Par exemple, vous pouvez utiliser JavaScript pour basculer la visibilité du conteneur multimédia :
        var mediaContainer = document.querySelector('.media');
        mediaContainer.style.display = 'block'; // Ajustez cela en fonction de vos besoins spécifiques
    });
});
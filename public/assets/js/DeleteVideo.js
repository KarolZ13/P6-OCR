document.addEventListener("DOMContentLoaded", function () {
    var deleteButtons = document.querySelectorAll('.delete-video-btn');

    deleteButtons.forEach(function (button) {
        button.addEventListener('click', function (event) {
            event.preventDefault();

            var deleteUrl = button.getAttribute('data-deletea-url');

            // Appel AJAX pour supprimer l'image
            fetch(deleteUrl, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                // Gérez la réponse JSON, par exemple, actualisez la page si nécessaire
                console.log(data);
                location.reload();
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    });
});
document.addEventListener("DOMContentLoaded", function () {
    var deleteButtons = document.querySelectorAll('.delete-picture-btn');

    deleteButtons.forEach(function (button) {
        button.addEventListener('click', function (event) {
            event.preventDefault();

            var deleteUrl = button.getAttribute('data-delete-url');
            
            var xhr = new XMLHttpRequest();
            xhr.open("DELETE", deleteUrl);
            xhr.setRequestHeader("Content-Type", "application/json");

            xhr.onload = function () {
                if (xhr.status === 200) {
                    var data = JSON.parse(xhr.responseText);
                    console.log(data);
                    location.reload();
                } else {
                    console.error('Request failed. Status: ' + xhr.status);
                }
            };

            xhr.send();
        });
    });
});

jQuery(document).ready(function() {
    var $videoContainer = $('.video-container');
    var $videoList = $videoContainer.find('.video');

    $videoContainer.on('click', '.add-another-video', function(e) {
        e.preventDefault();

        var prototype = $videoList.data('prototype');
        var index = $videoList.children().length;

        var newForm = prototype.replace(/__name__/g, index);
        $videoList.append('<li data-index="' + index + '">' + newForm + '</li>');

        updateVideoIndexes(); // Appeler la fonction pour mettre à jour les index après l'ajout
    });
});

function updateVideoIndexes() {
    $videoContainer.find('.video li').each(function(index) {
        $(this).attr('data-index', index);
        $(this).find(':input').each(function() {
            var newName = $(this).attr('name').replace(/\[\d+\]/g, '[' + index + ']');
            $(this).attr('name', newName);
            $(this).attr('id', $(this).attr('id').replace(/_\d+_/g, '_' + index + '_'));
        });
    });
}
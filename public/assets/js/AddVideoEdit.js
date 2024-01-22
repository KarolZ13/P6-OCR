jQuery(document).ready(function() {
    var $videoContainer = $('.video-container');
    var $videoList = $videoContainer.find('.video');

    // Calculate the starting index based on the existing videos
    var startingIndex = $videoList.children().length;

    $videoContainer.on('click', '.add-another-video', function(e) {
        e.preventDefault();

        // Calculate the new index before adding
        var newIndex = $videoList.children().length + startingIndex;

        // Output the calculated index
        console.log('Adding new video form at index:', newIndex);

        var prototype = $videoList.data('prototype');

        var newForm = prototype.replace(/__name__/g, newIndex);

        $videoList.append('<li data-index="' + newIndex + '">' + newForm + '</li>');

        updateVideoIndexes(); // Call the function to update indexes after adding
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
});

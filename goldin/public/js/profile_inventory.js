$(document).ready(function() {
    function updateTimes() {
        $('.time-purchased').each(function(index, element) {
            try {
                const timestamp = $(element).data('timestamp');
                const timeAgo = timeago.format(timestamp);
                $(element).text(`Purchased: ${timeAgo}`);
            } catch (error) {
                console.error('Error updating time:', error);
            }
        });
    }

    // Call updateTimes once to initialize the times
    updateTimes();

    // Set an interval to update the times every second
    setInterval(updateTimes, 1000);
});

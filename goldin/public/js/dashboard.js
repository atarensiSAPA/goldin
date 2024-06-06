$(document).ready(function() {
    $('.card').hover(
        function() { // mouse enter
            $(this).find('.add-to-cart-button').show();
        },
        function() { // mouse leave
            $(this).find('.add-to-cart-button').hide();
        }
    );
    $('.clothesDiv').on('click', function() {
        $('#clothesDescription').text('description');

        // Abre el modal
        let myModal = new bootstrap.Modal(document.getElementById('clothesModal'));
        myModal.show();
    });
});
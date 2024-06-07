$(document).ready(function() {
    // Esta función muestra u oculta el botón de "Añadir al carrito" cuando el mouse entra o sale de una tarjeta
    $('.card').hover(
        function() { // mouse enter
            $(this).find('.add-to-cart-button').show();
        },
        function() { // mouse leave
            $(this).find('.add-to-cart-button').hide();
        }
    );

    // Esta función maneja el evento de clic en una tarjeta de ropa para mostrar la descripción en el modal
    $('.clothesDiv').on('click', function() {
        // Obtener la información de la ropa desde el div
        let clothesName = $(this).data('clothes-name');
        let clothesType = $(this).data('clothes-type');
        let clothesPrice = $(this).data('clothes-price');
        let clothesUnits = $(this).data('clothes-units');
        let clothesImg = $(this).data('clothes-img');
        let clothesId = $(this).data('clothes-id');

        // Insertar la información en el modal
        $('#clothesDescription').html(`
            <img src="images/clothes/${clothesImg}" alt="" class="img-fluid rounded-0">
            <p>Name: ${clothesName}</p>
            <p>Type: ${clothesType}</p>
            <p class="d-flex align-items-center">Price: ${clothesPrice}<img src="images/user_coin.png" alt="coin" width="30" height="30" class="ms-0"></p>
            <p>Units: ${clothesUnits}</p>
        `);

        // Cambiar el ID del botón existente en el modal
        $('.btnCart').attr('id', `addToCartButton-${clothesId}`);

        // Abrir el modal
        let myModal = new bootstrap.Modal(document.getElementById('clothesModal'));
        myModal.show();
    });

    // Esta función maneja el evento de clic en el botón "Añadir al carrito"
    $(document).on('click', '.add-to-cart-button', function(event) {
        event.stopPropagation(); // Evitar la propagación del evento para que el modal no se cierre automáticamente
        let clothesDiv = $(this).parent().find('.clothesDiv');
        let clothesName = $(clothesDiv).data('clothes-name');
        let clothesType = $(clothesDiv).data('clothes-type');
        let clothesPrice = $(clothesDiv).data('clothes-price');
        let clothesUnits = $(clothesDiv).data('clothes-units');
        let clothesImg = $(clothesDiv).data('clothes-img');
        let clothesId = $(clothesDiv).data('clothes-id');
        
        let item = {
            id: clothesId,
            name: clothesName,
            price: clothesPrice,
            clothes_img: clothesImg
        };
        
        addToCart(item); // Añadir el artículo al carrito llamando a la funcion del archivo nav.js
        alert('Item added to cart successfully!');
    });

    // Esta función obtiene los elementos del carrito del sessionStorage
    function getCartItems() {
        return JSON.parse(sessionStorage.getItem('cartItems')) || [];
    }
});

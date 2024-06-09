import * as Cart from './cart.js';
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
            ${clothesUnits == 0 ? '<p class="text-danger text-center fs-4">Sold Out</p>' : ''}
        `);
    
        // Cambiar el ID del botón existente en el modal
        $('.btnCart').attr('id', `addToCartButton-${clothesId}`);
        $('.btnCart').attr('data-clothes-id', clothesId);
        $('.btnCart').attr('data-clothes-name', clothesName);
        $('.btnCart').attr('data-clothes-type', clothesType);
        $('.btnCart').attr('data-clothes-price', clothesPrice);
        $('.btnCart').attr('data-clothes-img', clothesImg);
    
        // Comprobar si las unidades están en 0
        if (clothesUnits == 0) {
            // Si las unidades están en 0, deshabilitar el botón
            $('.btnCart').prop('disabled', true);
        } else {
            // Si las unidades no están en 0, habilitar el botón
            $('.btnCart').prop('disabled', false);
        }
    
        // Abrir el modal
        let myModal = new bootstrap.Modal(document.getElementById('clothesModal'));
        myModal.show();
    });

    // Esta función maneja el evento de clic en el botón "Añadir al carrito"
    $(document).on('click', '.add-to-cart-button', function(event) {
        event.stopPropagation(); // Evitar la propagación del evento para que el modal no se cierre automáticamente
        let clothesName = $(this).data('clothes-name');
        let clothesType = $(this).data('clothes-type');
        let clothesPrice = $(this).data('clothes-price');
        let clothesUnits = $(this).data('clothes-units');
        let clothesImg = $(this).data('clothes-img');
        let clothesId = $(this).data('clothes-id');
        
        let item = {
            id: clothesId,
            name: clothesName,
            type: clothesType,
            price: clothesPrice,
            units: clothesUnits,
            clothes_img: clothesImg
        };
        
        addToCart(userId, item);
        alert('Item added to cart successfully!');
        location.reload();
    });

    // Esta función obtiene los elementos del carrito de las cookies
    function getCartItems(userId) {
        return JSON.parse(getCookie(`cartItems-${userId}`)) || [];
    }
    
    // Carga inicial del carrito
    function loadCartItems() {
        let cartItems = getCartItems(userId);
        Cart.updateCartDisplay(cartItems);
    }

    loadCartItems();

    function addToCart(userId, item) {
        let cartItems = getCartItems(userId);
        let existingItem = cartItems.find(cartItem => cartItem.id === item.id);
        if (existingItem) {
            existingItem.quantity++;
        } else {
            item.quantity = 1;
            cartItems.push(item);
        }
        Cart.saveCartItems(userId, cartItems);
    }
});
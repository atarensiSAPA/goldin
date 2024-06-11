import * as Cart from './cart.js';
import * as Profile from './profile.js';
$(document).ready(function() {
    try {
        // This function shows or hides the "Add to Cart" button when mouse enters or leaves a card
        $('.card').hover(
            function() { // mouse enter
                $(this).find('.add-to-cart-button').show();
            },
            function() { // mouse leave
                $(this).find('.add-to-cart-button').hide();
            }
        );

        $('.clothesDiv').on('keydown', function(event) {
            // Verificar si la tecla presionada es Enter (código 13)
            if (event.keyCode === 13) {
                // Evitar el comportamiento predeterminado del enter (como enviar un formulario)
                event.preventDefault();
                // Simular un clic en el elemento para abrir el modal
                $(this).click();
            }
        });
        let myModal = new bootstrap.Modal(document.getElementById('clothesModal'));
        // This function handles the click event on a clothes card to show the description in the modal
        $('.clothesDiv').on('click', function() {
            try {
                // Get clothes information from the div
                let clothesName = $(this).data('clothes-name');
                let clothesType = $(this).data('clothes-type');
                let clothesPrice = $(this).data('clothes-price');
                let clothesUnits = $(this).data('clothes-units');
                let clothesImg = $(this).data('clothes-img');
                let clothesId = $(this).data('clothes-id');
            
                // Insert the information into the modal
                $('#clothesDescription').html(`
                    <img src="images/clothes/${clothesImg}" alt="" class="img-fluid rounded-0">
                    <p>Name: ${clothesName}</p>
                    <p>Type: ${clothesType}</p>
                    <p class="d-flex align-items-center">Price: ${clothesPrice}<img src="images/user_coin.png" alt="coin" width="30" height="30" class="ms-0"></p>
                    <p>Units: ${clothesUnits}</p>
                    ${clothesUnits == 0 ? '<p class="text-danger text-center fs-4">Sold Out</p>' : ''}
                `);
            
                // Change the ID of the existing button in the modal
                $('.btnCart').attr('id', `addToCartButton-${clothesId}`);
                $('.btnCart').attr('data-clothes-id', clothesId);
                $('.btnCart').attr('data-clothes-name', clothesName);
                $('.btnCart').attr('data-clothes-type', clothesType);
                $('.btnCart').attr('data-clothes-price', clothesPrice);
                $('.btnCart').attr('data-clothes-img', clothesImg);
            
                // Check if units are 0
                if (clothesUnits == 0) {
                    // If units are 0, disable the button
                    $('.btnCart').prop('disabled', true);
                } else {
                    // If units are not 0, enable the button
                    $('.btnCart').prop('disabled', false);
                }
            
                // Open the modal
                myModal.show();
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred while processing the clothes description.');
            }
        });


        // This function handles the click event on the "Add to Cart" button
        $(document).on('click', '.add-to-cart-button', function(event) {
            try {
                event.stopPropagation(); // Prevent event propagation to avoid modal from closing automatically
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
                myModal.hide();
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred while adding the item to the cart.');
            }
        });

        // This function gets cart items from cookies
        function getCartItems(userId) {
            return JSON.parse(getCookie(`cartItems-${userId}`)) || [];
        }
        
        // Initial loading of cart items
        function loadCartItems() {
            let cartItems = getCartItems(userId);
            Cart.updateCartDisplay(cartItems);
        }

        loadCartItems();

        // This function adds an item to the cart
        function addToCart(userId, item) {
            try {
                let cartItems = getCartItems(userId);
                let existingItem = cartItems.find(cartItem => cartItem.id === item.id);
                if (existingItem) {
                    existingItem.quantity++;
                } else {
                    item.quantity = 1;
                    cartItems.push(item);
                }
                Cart.saveCartItems(userId, cartItems);
        
                // Show alert message
                Profile.displayAlert('alertUpdateCart', 'alert-messageUpdateCart', 'Item added to cart successfully!');
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred while adding the item to the cart.');
            }
        }
    } catch (error) {
        console.error('Error:', error);
        alert('An unexpected error occurred. Please try again.');
    }
});

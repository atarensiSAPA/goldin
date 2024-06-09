import { payForm, openCard} from './profile.js';
import * as Cart from './cart.js';

$(document).ready(function() {
    let cartItems = Cart.getCartItems(userId)
    Cart.addEventListenerBtn('#clothesCart')
    Cart.updateCartDisplay(cartItems, '#clothesCart');
});

openCard("credit_cardBtn")

$('#submitPayment').click(function(e) {
    e.preventDefault(); // Prevent the default form submission

    let cardInfo = payForm();
    if (!cardInfo) {
        return;
    }

    let cartItems = Cart.getCartItems(userId);
    console.log("Cart Items:", cartItems); // Log cart items

    let csrfToken = $('meta[name="csrf-token"]').attr('content');
    console.log("CSRF Token:", csrfToken); // Log CSRF token

    $.ajax({
        url: '/buyCart',
        method: 'POST',
        data: {
            cardInfo: cardInfo,
            cartItems: cartItems,
            _token: csrfToken, // Correctly get CSRF token
        },
        success: function(data) {
            console.log("AJAX Success:", data);
            Cart.clearCart(userId);
            location.reload();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error('Error details:', textStatus, errorThrown, jqXHR.responseText);
            alert('An error occurred: ' + textStatus + ' - ' + errorThrown + '\n' + jqXHR.responseText);
        }
    });
});
// Import necessary functions from modules
import { payForm, openCard } from './profile.js';
import * as Cart from './cart.js';

// Get cart items from the cart module
let cartItems = Cart.getCartItems(userId);

$(document).ready(function() {
    try {
        // Add event listeners to buttons in the clothes cart modal
        Cart.addEventListenerBtn('#clothesCart');

        // Update the cart display in the clothes cart modal
        Cart.updateCartDisplay(cartItems, '#clothesCart');
    } catch (error) {
        console.error('Error on document ready:', error);
        alert('An error occurred while initializing the document.');
    }
});

// Open the payment card form when clicking the credit card button
openCard("credit_cardBtn");

// Event listener for the payment submission
$('#submitPayment').click(function(e) {
    if (cartItems.length === 0) {
        alert('Your cart is empty. Please add items before proceeding to payment.');
        return;
    }
    try {
        e.preventDefault(); // Prevent the default form submission

        // Get card information from the payForm function
        let cardInfo = payForm();
        if (!cardInfo) {
            return;
        }

        // Get updated cart items after potential changes
        let cartItems = Cart.getCartItems(userId);
        console.log("Cart Items:", cartItems); // Log cart items

        // Get CSRF token from meta tag
        let csrfToken = $('meta[name="csrf-token"]').attr('content');
        console.log("CSRF Token:", csrfToken); // Log CSRF token

        // Send payment request via AJAX
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
                Cart.clearCart(userId); // Clear the cart after successful purchase
                location.reload(); // Reload the page to reflect changes
                alert('Purchase completed successfully!'); // Show success message
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Error details:', textStatus, errorThrown, jqXHR.responseText);
                let serverResponse = JSON.parse(jqXHR.responseText);
                alert(serverResponse.message); // Show error message from the server
            }
        });
    } catch (error) {
        console.error('Error in payment submission:', error);
        alert('An error occurred during payment submission.');
    }
});

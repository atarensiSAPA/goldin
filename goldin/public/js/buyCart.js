// Importa las funciones necesarias
import { payForm, openCard, displayAlert, setLocalStorageItem, removeLocalStorageItem } from './profile.js';
import * as Cart from './cart.js';

// Espera a que el DOM esté completamente cargado
$(document).ready(function() {
    try {
        // Obtiene los elementos del carrito
        let cartItems = Cart.getCartItems(userId);

        // Agrega event listeners a los botones en el modal del carrito de ropa
        Cart.addEventListenerBtn('#clothesCart');

        // Actualiza la visualización del carrito en el modal del carrito de ropa
        Cart.updateCartDisplay(cartItems, '#clothesCart');

        // Abre el formulario de tarjeta de pago cuando se hace clic en el botón de tarjeta de crédito
        openCard("credit_cardBtn");

        // Maneja el evento de envío del pago
        $('#submitPayment').click(function(e) {
            e.preventDefault(); // Previene la sumisión del formulario por defecto

            // Valida el formulario de pago y obtiene la información de la tarjeta
            const cardInfo = payForm();
            if (!cardInfo) {
                return;
            }

            // Obtiene los elementos del carrito actualizados después de posibles cambios
            let cartItems = Cart.getCartItems(userId);

            // Obtiene el token CSRF del meta tag
            let csrfToken = $('meta[name="csrf-token"]').attr('content');

            // Envía la solicitud de pago a través de AJAX
            $.ajax({
                url: '/buyCart',
                method: 'POST',
                data: {
                    cardInfo: cardInfo,
                    cartItems: cartItems,
                    _token: csrfToken,
                },
                success: function(data) {
                    console.log("AJAX Success:", data);
                    Cart.clearCart(userId); // Limpia el carrito después de una compra exitosa
                    setLocalStorageItem('showalertBuyCart', 'true');
                    sendBuyMail(); // Envía un correo electrónico de confirmación de compra
                    location.reload(); // Recarga la página para reflejar los cambios
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('Error details:', textStatus, errorThrown, jqXHR.responseText);
                    let serverResponse = JSON.parse(jqXHR.responseText);
                    alert(serverResponse.message); // Muestra un mensaje de error del servidor
                }
            });

            function sendBuyMail(){
                $.ajax({
                    url: '/sendBuyMail',
                    method: 'POST',
                    data: {
                        cartItems: cartItems,
                        _token: csrfToken,
                    },
                    success: function(data) {
                        console.log("Mail sent");
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error('Error details:', textStatus, errorThrown, jqXHR.responseText);
                        let serverResponse = JSON.parse(jqXHR.responseText);
                        alert(serverResponse.message); // Muestra un mensaje de error del servidor
                    }
                });
            }
        });
    } catch (error) {
        console.error('Error:', error);
        alert('An unexpected error occurred. Please try again.');
    }
});

// Muestra alertas si es necesario cuando la página se carga
window.onload = function() {
    try {
        if (localStorage.getItem('showalertBuyCart') === 'true') {
            displayAlert('alertBuyCart', 'alert-messageBuyCart', 'Purchase completed successfully!');
            removeLocalStorageItem('showalertBuyCart');
        }
    } catch (error) {
        console.error('Error in window onload handler:', error);
    }
}

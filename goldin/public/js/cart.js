// Save cart items to cookies
export function saveCartItems(userId, cartItems) {
    try {
        setCookie(`cartItems-${userId}`, JSON.stringify(cartItems), 7);
    } catch (error) {
        console.error('Error saving cart items:', error);
        throw new Error('An error occurred while saving cart items.');
    }
}

// Retrieve cart items from cookies
export function getCartItems(userId) {
    try {
        return JSON.parse(getCookie(`cartItems-${userId}`)) || [];
    } catch (error) {
        console.error('Error getting cart items:', error);
        throw new Error('An error occurred while getting cart items.');
    }
}

// Increase quantity of a cart item
function increaseQuantity(userId, index, div) {
    try {
        let cartItems = getCartItems(userId);
        if (index >= 0 && index < cartItems.length) {
            let item = cartItems[index];
            item.quantity++;
            saveCartItems(userId, cartItems);
            updateCartDisplay(cartItems, div);
        } else {
            console.error('Index out of range');
            throw new Error('Index out of range.');
        }
    } catch (error) {
        console.error('Error increasing quantity:', error);
        alert('An error occurred while increasing quantity.');
    }
}

// Decrease quantity of a cart item
function decreaseQuantity(userId, index, div) {
    try {
        let cartItems = getCartItems(userId);
        let item = cartItems[index];
        if (item.quantity > 1) {
            item.quantity--;
        } else {
            removeFromCart(userId, index);
        }
        saveCartItems(userId, cartItems);
        updateCartDisplay(cartItems, div);
    } catch (error) {
        console.error('Error decreasing quantity:', error);
        alert('An error occurred while decreasing quantity.');
    }
}

// Remove an item from the cart with confirmation
function removeFromCart(userId, index, div) {
    try {
        // Mostrar modal de confirmación
        $('#confirmationModal').modal('show');

        // Agregar un event listener al botón de confirmación del modal
        $('#confirmationModal').on('click', '#confirmRemoveButton', function() {
            let cartItems = getCartItems(userId);
            cartItems.splice(index, 1);
            saveCartItems(userId, cartItems);
            updateCartDisplay(cartItems, div);
            $('#confirmationModal').modal('hide'); // Ocultar el modal de confirmación
        });
    } catch (error) {
        console.error('Error removing from cart:', error);
        alert('An error occurred while removing from cart.');
    }
}


// Clear the cart
export function clearCart() {
    try {
        eraseCookie(`cartItems-${userId}`);
    } catch (error) {
        console.error('Error clearing cart:', error);
        alert('An error occurred while clearing cart.');
    }
}

// Update the display of the cart
export function updateCartDisplay(cartItems, div) {
    try {
        let cartModal = $(div);
        cartModal.empty();
        let total = 0;
        let itemsToRemove = [];

        cartItems.forEach((item, index) => {
            if (item.units <= 0) {
                itemsToRemove.push(index);
            } else {
                let cartItemHTML = `
                    <div class="mb-4 bg-gray-100 dark:bg-gray-900 rounded">
                        <div class="flex justify-between items-center">
                            <div class="flex items-center">
                                <img src="images/clothes/${item.clothes_img}" alt="${item.name}" class="w-25 h-auto object-contain mt-2 ml-3">
                                <div class="ml-4">
                                    <h3 class="font-medium textModal">${item.name}</h3>
                                    <p class="d-flex textModal">${item.price} <img src="images/user_coin.png" alt="coin" width="25" height="25" class="ms-1"></p>
                                    <div class="flex items-center">
                                        <button type="button" class="mr-2 decrease-button">-</button>
                                        <p class="text-sm textModal">Quantity: ${item.quantity}</p>
                                        <button type="button" class="ml-2 increase-button">+</button>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="text-red-600 hover:text-red-400 focus:outline-none remove-cart-button" data-item-index="${index}">
                                Remove
                            </button>
                        </div>
                    </div>
                `;
                cartModal.append(cartItemHTML);
                total += item.price * item.quantity;
            }
        });

        itemsToRemove.forEach(index => {
            removeFromCart(userId, index, div);
        });

        if (cartItems.length === 0 || total === 0) {
            let emptyCartHTML = `
                <div class="mt-4 bg-gray-100 dark:bg-gray-900 rounded flex items-center justify-center py-4">
                    <h2 class="text-lg font-medium">Add something to the cart!</h2>
                </div>
            `;
            cartModal.append(emptyCartHTML);
        } else {
            let totalHTML = `
                <div class="mt-4 bg-gray-100 dark:bg-gray-900 rounded py-2">
                    <h2
2 class="text-lg font-medium d-flex marginLeft">Total: ${total} <img src="images/user_coin.png" alt="coin" width="30" height="30" class="ms-1"></h2>
                </div>
            `;
            cartModal.append(totalHTML);
        }
    } catch (error) {
        console.error('Error updating cart display:', error);
        alert('An error occurred while updating cart display.');
    }
}

// Document ready function
$(document).ready(function() {
    try {
        // Add event listeners to buttons
        addEventListenerBtn('#cartModal .modal-body');

        // Show cart modal on click
        $('.btnCartOpener').on('click', function() {
            let cartItems = getCartItems(userId);
            updateCartDisplay(cartItems, '#cartModal .modal-body');
            let myModal = new bootstrap.Modal(document.getElementById('cartModal'));
            myModal.show();
        });
    } catch (error) {
        console.error('Error on document ready:', error);
        alert('An error occurred while initializing the document.');
    }
});

// Add event listeners to buttons
export function addEventListenerBtn(div) {
    try {
        $(div).on('click', '.increase-button', function() {
            let index = $(this).closest('.mb-4').index();
            increaseQuantity(userId, index, div);
        });

        $(div).on('click', '.decrease-button', function() {
            let index = $(this).closest('.mb-4').index();
            decreaseQuantity(userId, index, div);
        });

        $(div).on('click', '.remove-cart-button', function() {
            let index = $(this).closest('.mb-4').index();
            removeFromCart(userId, index, div);
        });
    } catch (error) {
        console.error('Error adding event listeners:', error);
        alert('An error occurred while adding event listeners.');
    }
}
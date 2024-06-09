export function saveCartItems(userId, cartItems) {
    setCookie(`cartItems-${userId}`, JSON.stringify(cartItems), 7);
}

export function getCartItems(userId) {
    return JSON.parse(getCookie(`cartItems-${userId}`)) || [];
}

function increaseQuantity(userId, index, div){
    console.log(index);
    let cartItems = getCartItems(userId);
    if (index >= 0 && index < cartItems.length) {
        let item = cartItems[index];
        item.quantity++;
        saveCartItems(userId, cartItems);
        updateCartDisplay(cartItems, div);
    } else {
        console.error("Índice fuera de rango");
    }
}

function decreaseQuantity(userId, index, div){
    let cartItems = getCartItems(userId);
    let item = cartItems[index];
    if (item.quantity > 1) {
        item.quantity--;
    } else {
        removeFromCart(userId, index);
    }
    saveCartItems(userId, cartItems);
    updateCartDisplay(cartItems, div);
}

function removeFromCart(userId, index, div){
    let cartItems = getCartItems(userId);
    cartItems.splice(index, 1);
    saveCartItems(userId, cartItems);
    updateCartDisplay(cartItems, div);
}

export function clearCart(){
    eraseCookie(`cartItems-${userId}`);
}

export function updateCartDisplay(cartItems, div) {
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
                            <img src="images/clothes/${item.clothes_img}" alt="${item.name}" class="w-25 h-auto object-contain">
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
                <h2 class="text-lg font-medium d-flex marginLeft">Total: ${total} <img src="images/user_coin.png" alt="coin" width="30" height="30" class="ms-1"></h2>
            </div>
        `;
        cartModal.append(totalHTML);
    }
}

$(document).ready(function() {
    addEventListenerBtn('#cartModal .modal-body');

    $('.btnCartOpener').on('click', function() {
        let cartItems = getCartItems(userId);
        updateCartDisplay(cartItems, '#cartModal .modal-body');
        let myModal = new bootstrap.Modal(document.getElementById('cartModal'));
        myModal.show();
    });
});

export function addEventListenerBtn(div){
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
}
function saveCartItems(userId, cartItems) {
    setCookie(`cartItems-${userId}`, JSON.stringify(cartItems), 7);
}

function getCartItems(userId) {
    return JSON.parse(getCookie(`cartItems-${userId}`)) || [];
}

function increaseQuantity(userId, index){
    console.log(index);
    let cartItems = getCartItems(userId);
    if (index >= 0 && index < cartItems.length) {
        let item = cartItems[index];
        item.quantity++;
        saveCartItems(userId, cartItems);
        updateCartDisplay(cartItems);
    } else {
        console.error("Índice fuera de rango");
    }
}

function decreaseQuantity(userId, index){
    let cartItems = getCartItems(userId);
    let item = cartItems[index];
    if (item.quantity > 1) {
        item.quantity--;
    } else {
        removeFromCart(userId, index);
    }
    saveCartItems(userId, cartItems);
    updateCartDisplay(cartItems);
}

function removeFromCart(userId, index){
    let cartItems = getCartItems(userId);
    cartItems.splice(index, 1);
    saveCartItems(userId, cartItems);
    updateCartDisplay(cartItems);
}

function updateCartDisplay(cartItems) {
    let cartModal = $('#cartModal .modal-body');
    cartModal.empty();
    let total = 0;
    cartItems.forEach((item, index) => {
        let cartItemHTML = `
            <div class="mb-4 bg-gray-100 dark:bg-gray-900">
                <div class="flex justify-between items-center">
                    <div class="flex items-center">
                        <img src="images/clothes/${item.clothes_img}" alt="${item.name}" class="w-25 h-auto object-contain">
                        <div class="ml-4">
                            <h3 class="text-sm font-medium">${item.name}</h3>
                            <p class="text-sm">${item.price} coins</p>
                            <div class="flex items-center">
                                <button type="button" class="mr-2 decrease-button">-</button>
                                <p class="text-sm">Quantity: ${item.quantity}</p>
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
    });
    let totalHTML = `
        <div class="mt-4 bg-gray-100 dark:bg-gray-900">
            <h2 class="text-lg font-medium">Total: ${total} coins</h2>
        </div>
    `;
    cartModal.append(totalHTML);
}

$(document).ready(function() {
    $(document).on('click', '.increase-button', function() {
        let index = $(this).closest('.mb-4').index();
        increaseQuantity(userId, index);
    });

    $(document).on('click', '.decrease-button', function() {
        let index = $(this).closest('.mb-4').index();
        decreaseQuantity(userId, index);
    });

    $(document).on('click', '.remove-cart-button', function() {
        let index = $(this).closest('.mb-4').index();
        removeFromCart(userId, index);
    });

    $('.btnCartOpener').on('click', function() {
        let cartItems = getCartItems(userId);
        updateCartDisplay(cartItems);
        let myModal = new bootstrap.Modal(document.getElementById('cartModal'));
        myModal.show();
    });
});
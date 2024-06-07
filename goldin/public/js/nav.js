let cartItems = [];

window.loadCartItems = () => {
    cartItems = JSON.parse(sessionStorage.getItem('cartItems')) || [];
    console.log(cartItems);
    updateCartDisplay(cartItems);
}

window.saveCartItems = () => {
    sessionStorage.setItem('cartItems', JSON.stringify(cartItems));
}

window.addToCart = (item) => {
    let existingItem = cartItems.find(cartItem => cartItem.id === item.id);
    if (existingItem) {
        existingItem.quantity++;
    } else {
        item.quantity = 1;
        cartItems.push(item);
    }
    window.saveCartItems();
}

window.removeFromCart = (index) => {
    cartItems.splice(index, 1);
    window.saveCartItems();
    updateCartDisplay(cartItems);
}

$('.btnCartOpener').on('click', function() {
    let cartItems = JSON.parse(sessionStorage.getItem('cartItems')) || [];
    updateCartDisplay(cartItems);
    let myModal = new bootstrap.Modal(document.getElementById('cartModal'));
    myModal.show();
});

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
                                <button onclick="decreaseQuantity(${index})" class="mr-2">-</button>
                                <p class="text-sm">Quantity: ${item.quantity}</p>
                                <button onclick="increaseQuantity(${index})" class="ml-2">+</button>
                            </div>
                        </div>
                    </div>
                    <button onclick="removeFromCart(${index})" class="text-red-600 hover:text-red-400 focus:outline-none">
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

window.increaseQuantity = (index) => {
    let item = cartItems[index];
    item.quantity++;
    window.saveCartItems();
    updateCartDisplay(cartItems);
}

window.decreaseQuantity = (index) => {
    let item = cartItems[index];
    if (item.quantity > 1) {
        item.quantity--;
    } else {
        window.removeFromCart(index);
    }
    window.saveCartItems();
    updateCartDisplay(cartItems);
}

$(document).ready(function() {
    window.loadCartItems();
});

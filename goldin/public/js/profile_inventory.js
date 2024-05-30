document.addEventListener('DOMContentLoaded', function() {
    let weaponIdToWithdraw;
    let withdrawConfirmModal = new bootstrap.Modal(document.getElementById('withdrawConfirmModal'));
    let weaponIdToSell;
    let confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));
    // Obtener el token CSRF
    let token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    $(document).on('click', '.withdraw-button', function() {
        weaponIdToWithdraw = $(this).data('weapon-id');
        withdrawConfirmModal.show();
    });

    $(document).on('click', '#withdrawConfirmButton', function() {
        $.ajax({
            url: '/withdraw-weapon',
            method: 'POST',
            data: {
                weapon_id: weaponIdToWithdraw,
                _token: token
            },
            dataType: 'json',
            success: function(response) {
                console.log('Response:', response);
                if (response.success) {
                    showAlert('Weapon withdrawn successfully. A confirmation email has been sent.');
                    updateWeaponsList();
                    withdrawConfirmModal.hide();
                } else {
                    // Si hay un error, mostrar el alerta
                    showAlertUnits(response.error);
                    withdrawConfirmModal.hide();
                }
            },
            error: function(xhr, status, error) {
                console.log('AJAX error:', status, error);
                if (xhr.status === 400) {
                    showAlertUnits('No units available, please try again later');
                    withdrawConfirmModal.hide();
                }
            }
        });
    });
    

    // Mostrar el alerta si está en el localStorage
    if (localStorage.getItem('showAlertWeaponUnits') === 'true') {
        showAlertUnits('No units available, please try again later');
        localStorage.removeItem('showAlertWeaponUnits');
    }

    // Obtener el filtro seleccionado del localStorage
    let selectedFilter = localStorage.getItem('selectedFilter');
    if (selectedFilter) {
        document.getElementById('filter').value = selectedFilter;
    }

    // Mostrar el alert si está en el localStorage
    if (localStorage.getItem('showAlertWeapon') === 'true') {
        showAlert('You sold the weapon');
        localStorage.removeItem('showAlertWeapon');
    }

    // Manejar el click en el botón de vender
    $(document).on('click', '.sell-button', function() {
        weaponIdToSell = $(this).data('weapon-id');
        console.log(weaponIdToSell);
        // Mostrar el modal de confirmación
        confirmModal.show();
    });

    // Manejar el click en el botón de confirmar venta
    $(document).on('click', '#confirmButton', function() {
        $.ajax({
            url: '/sell-weapon',
            method: 'POST',
            data: {
                weapon_id: weaponIdToSell,
                _token: token
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    showAlert('You sold the weapon');
                    updateWeaponsList();  // Llama a una función para actualizar la lista de armas
                    updateCoins(response.newCoinBalance);  // Actualiza la cantidad de monedas
                    confirmModal.hide();  // Ocultar el modal de confirmación
                } else {
                    alert('Hubo un error al vender el arma. Por favor, inténtalo de nuevo.');
                }
            }
        });
    });

    // Manejar el cambio en el filtro
    $('#filter').change(function() {
        let filter = $(this).val();

        // Guardar el filtro seleccionado en el localStorage
        localStorage.setItem('selectedFilter', filter);

        // Enviar el filtro al servidor y obtener las armas filtradas
        $.ajax({
            url: '/filter-weapons',
            method: 'POST',
            data: {
                filter: filter,
                _token: token
            },
            dataType: 'json',
            success: function(data) {
                renderWeapons(data.weapons);
            }
        });
    });

    // Mostrar el alert
    function showAlert(message) {
        var alertDiv = document.getElementById('alertWeapon');
        alertDiv.style.display = 'block';
        document.getElementById('alert-messageWeapon').textContent = message;

        setTimeout(function() {
            $(alertDiv).fadeOut(1000, function() {
                $(this).css('display', 'none');
            });
        }, 3000);
    }
    // Mostrar el alerta de unidades
    function showAlertUnits(message) {
        var alertDiv = document.getElementById('alertWeaponUnits');
        alertDiv.style.display = 'block';
        document.getElementById('alert-messageWeaponUnits').textContent = message;

        setTimeout(function() {
            $(alertDiv).fadeOut(1000, function() {
                $(this).css('display', 'none');
            });
        }, 3000);
    }

    // Función para actualizar la lista de armas
    function updateWeaponsList() {
        let filter = $('#filter').val();
        $.ajax({
            url: '/filter-weapons',
            method: 'POST',
            data: {
                filter: filter,
                _token: token
            },
            dataType: 'json',
            success: function(data) {
                renderWeapons(data.weapons);
            }
        });
    }

    // Función para actualizar la cantidad de monedas
    function updateCoins(newBalance) {
        $('#coins').text(newBalance);
        let newBalanceText = 'Coins: ' + newBalance + ' <img src="/images/user_coin.png" alt="coin" width="30" height="30">';
        $('#profileCoins').html(newBalanceText);
    }

    // Función para renderizar las armas
    function renderWeapons(weapons) {
        let weaponsContainer = $('.d-flex.justify-content-center.mt-3.flex-wrap');
        weaponsContainer.empty();

        let filterElement = $('#filter');
        let filterLabel = $('label[for="filter"]');
    
        if (weapons.length === 0) {
            weaponsContainer.append('<p class="text-white">No tienes armas en tu inventario. ¡Abre cajas para conseguir más!</p>');
            filterElement.hide();  // Ocultar el filtro si no hay armas
            filterLabel.hide();    // Ocultar el texto del filtro si no hay armas
        } else {
            filterElement.show();  // Mostrar el filtro si hay armas
            filterLabel.show();    // Mostrar el texto del filtro si hay armas
            weapons.forEach(function(weapon) {
                let weaponDiv = $('<div>').addClass('mx-2 mb-3 dark:bg-gray-800 d-flex flex-column justify-content-between weapon-container')
                    .css({
                        'border': '3px solid ' + weapon.color,
                        'border-radius': '15px',
                        'padding': '10px 10px 5px 10px',
                        'color': 'white'
                    })
                    .attr('id', 'weapon-container-' + weapon.id);
        
                let img = $('<img>').attr({
                    'src': '/images/skins/' + weapon.weapon_img,
                    'alt': weapon.name,
                    'title': weapon.name,
                    'width': '235',
                    'height': '235'
                });
        
                let weaponInfo = $('<div>').addClass('mt-auto')
                    .append('<p>Weapon Name: ' + weapon.weapon_name + '</p>')
                    .append('<p>Skin Name: ' + weapon.weapon_skin.replace('_', ' ') + '</p>')
                    .append('<p class="d-flex align-items-center">Price: ' + weapon.price + '<img src="/images/user_coin.png" alt="coin" width="30" height="30"></p>');
        
                let buttons = $('<div>').addClass('d-flex justify-content-center weapon-buttons')
                    .append('<button type="button" class="btn btn-success sell-button me-3" data-weapon-id="' + weapon.id + '">Sell</button>')
                    .append('<button type="button" class="btn btn-primary withdraw-button" data-weapon-id="' + weapon.id + '">Withdraw</button>');
        
                weaponDiv.append(img, weaponInfo, buttons);
                weaponsContainer.append(weaponDiv);
            });
        }
    }
});

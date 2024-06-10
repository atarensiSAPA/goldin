document.addEventListener('DOMContentLoaded', function() {
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    $('#filter').change(function() {
        try {
            const filter = $(this).val();
            localStorage.setItem('selectedFilter', filter);
            filterWeapons(filter);
        } catch (error) {
            console.error('Error in filter change handler:', error);
        }
    });

    function filterWeapons(filter) {
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
            },
            error: function(xhr, status, error) {
                console.error('AJAX error in filter change:', status, error);
            }
        });
    }

    function displayAlert(alertId, messageId, message) {
        const alertDiv = document.getElementById(alertId);
        alertDiv.style.display = 'block';
        document.getElementById(messageId).textContent = message;

        setTimeout(function() {
            $(alertDiv).fadeOut(1000, function() {
                $(this).css('display', 'none');
            });
        }, 3000);
    }

    function renderWeapons(weapons) {
        const weaponsContainer = $('.d-flex.justify-content-center.mt-3.flex-wrap');
        weaponsContainer.empty();

        const filterElement = $('#filter');
        const filterLabel = $('label[for="filter"]');
    
        if (weapons.length === 0) {
            weaponsContainer.append('<p class="text-white">You have no weapons in your inventory. Open boxes to get more!</p>');
            filterElement.hide();
            filterLabel.hide();
        } else {
            filterElement.show();
            filterLabel.show();
            weapons.forEach(function(weapon) {
                const weaponDiv = $('<div>').addClass('mx-2 mb-3 dark:bg-gray-800 d-flex flex-column justify-content-between weapon-container')
                    .css({
                        'border': '3px solid ' + weapon.color,
                        'border-radius': '15px',
                        'padding': '10px 10px 5px 10px',
                        'color': 'white'
                    })
                    .attr('id', 'weapon-container-' + weapon.id);
        
                const img = $('<img>').attr({
                    'src': '/images/skins/' + weapon.weapon_img,
                    'alt': weapon.name,
                    'title': weapon.name,
                    'width':'235',
                    'height': '235'
                });
        
                const weaponInfo = $('<div>').addClass('mt-auto')
                    .append('<p>Weapon Name: ' + weapon.weapon_name + '</p>')
                    .append('<p>Skin Name: ' + weapon.weapon_skin.replace('_', ' ') + '</p>')
                    .append('<p class="d-flex align-items-center">Price: ' + weapon.price + '<img src="/images/user_coin.png" alt="coin" width="30" height="30"></p>');
        
                const buttons = $('<div>').addClass('d-flex justify-content-center weapon-buttons')
                    .append('<button type="button" class="btn btn-success sell-button me-3" data-weapon-id="' + weapon.id + '">Sell</button>')
                    .append('<button type="button" class="btn btn-primary withdraw-button" data-weapon-id="' + weapon.id + '">Withdraw</button>');
        
                weaponDiv.append(img, weaponInfo, buttons);
                weaponsContainer.append(weaponDiv);
            });
        }
    }

    // Lógica para mostrar alertas si es necesario
    function showLocalStorageAlerts() {
        showLocalStorageAlert('showAlertWeaponUnits', 'No units available, please try again later', 'alertWeaponUnits', 'alert-messageWeaponUnits');
        showLocalStorageAlert('showAlertWeapon', 'You sold the weapon', 'alertWeapon', 'alert-messageWeapon');
    }

    function showLocalStorageAlert(key, message, alertId, messageId) {
        if (localStorage.getItem(key) === 'true') {
            displayAlert(alertId, messageId, message);
            localStorage.removeItem(key);
        }
    }

    // Mostrar alertas si están en el localStorage
    showLocalStorageAlerts();
});

document.addEventListener('DOMContentLoaded', function() {
    let weaponIdToWithdraw;
    let withdrawConfirmModal = new bootstrap.Modal(document.getElementById('withdrawConfirmModal'));
    let weaponIdToSell;
    let confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));

    // Get the CSRF token
    let token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Handle withdraw button click
    $(document).on('click', '.withdraw-button', function() {
        weaponIdToWithdraw = $(this).data('weapon-id');
        withdrawConfirmModal.show();
    });

    // Handle withdraw confirmation button click
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
                    // If there is an error, show the alert
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

    // Show the alert if it is in localStorage
    if (localStorage.getItem('showAlertWeaponUnits') === 'true') {
        showAlertUnits('No units available, please try again later');
        localStorage.removeItem('showAlertWeaponUnits');
    }

    // Get the selected filter from localStorage
    let selectedFilter = localStorage.getItem('selectedFilter');
    if (selectedFilter) {
        document.getElementById('filter').value = selectedFilter;
    }

    // Show the alert if it is in localStorage
    if (localStorage.getItem('showAlertWeapon') === 'true') {
        showAlert('You sold the weapon');
        localStorage.removeItem('showAlertWeapon');
    }

    // Handle sell button click
    $(document).on('click', '.sell-button', function() {
        weaponIdToSell = $(this).data('weapon-id');
        console.log(weaponIdToSell);
        // Show the confirmation modal
        confirmModal.show();
    });

    // Handle sell confirmation button click
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
                    updateWeaponsList();  // Call a function to update the weapon list
                    updateCoins(response.newCoinBalance);  // Update the coin balance
                    confirmModal.hide();  // Hide the confirmation modal
                } else {
                    alert('There was an error selling the weapon. Please try again.');
                }
            }
        });
    });

    // Handle filter change
    $('#filter').change(function() {
        let filter = $(this).val();

        // Save the selected filter to localStorage
        localStorage.setItem('selectedFilter', filter);

        // Send the filter to the server and get the filtered weapons
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

    // Show the alert
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

    // Show the units alert
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

    // Function to update the weapons list
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

    // Function to update the coin balance
    function updateCoins(newBalance) {
        $('#coins').text(newBalance);
        let newBalanceText = 'Coins: ' + newBalance + ' <img src="/images/user_coin.png" alt="coin" width="30" height="30">';
        $('#profileCoins').html(newBalanceText);
    }

    // Function to render the weapons
    function renderWeapons(weapons) {
        let weaponsContainer = $('.d-flex.justify-content-center.mt-3.flex-wrap');
        weaponsContainer.empty();

        let filterElement = $('#filter');
        let filterLabel = $('label[for="filter"]');
    
        if (weapons.length === 0) {
            weaponsContainer.append('<p class="text-white">You have no weapons in your inventory. Open boxes to get more!</p>');
            filterElement.hide();  // Hide the filter if there are no weapons
            filterLabel.hide();    // Hide the filter label if there are no weapons
        } else {
            filterElement.show();  // Show the filter if there are weapons
            filterLabel.show();    // Show the filter label if there are weapons
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

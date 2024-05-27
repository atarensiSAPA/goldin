let btnCancelVip = document.getElementById('cancelVip');
let token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

$('#buyVip').click(function() {
    $('#cardDetails').toggle();
});

// Automatically move focus to the next input field after 4 digits
$('#cardNumber1, #cardNumber2, #cardNumber3, #cardNumber4').keyup(function() {
    if (this.value.length == this.maxLength) {
        $(this).nextAll('.form-control').first().focus();
    }
});

$('#submitPayment').click(function(e) {
    e.preventDefault();

    // Validar los campos
    let cardNumber = $('#cardNumber1').val() + $('#cardNumber2').val() + $('#cardNumber3').val() + $('#cardNumber4').val();
    let cardName = $('#cardName').val();
    let expiryDate = $('#expiryDate').val();
    let cvv = $('#cvv').val();
    let errorMessage = '';

    if (!/^\d{16}$/.test(cardNumber)) {
        errorMessage += 'Please enter a valid 16 digit card number.<br>';
    }
    
    if (!/^[a-zA-Z\s]+$/.test(cardName)) {
        errorMessage += 'Please enter a valid card name.<br>';
    }
    
    if (!/^(0[1-9]|1[0-2])\/\d{2}$/.test(expiryDate)) {
        errorMessage += 'Please enter a valid expiry date in the format MM/YY.<br>';
    } else {
        let today = new Date();
        let currentYear = today.getFullYear().toString().substr(-2);
        let currentMonth = ('0' + (today.getMonth() + 1)).slice(-2);
    
        let [expiryMonth, expiryYear] = expiryDate.split('/');
    
        if (expiryYear < currentYear || (expiryYear == currentYear && expiryMonth < currentMonth)) {
            errorMessage += 'Expiry date cannot be in the past.<br>';
        }
    }
    
    if (!/^\d{3}$/.test(cvv)) {
        errorMessage += 'Please enter a valid 3 digit CVV.<br>';
    }
    
    if (errorMessage) {
        document.getElementById('errorMessage').innerHTML = errorMessage;
        return;
    }

    let cardInfo = {
        cardNumber: cardNumber,
        cardName: cardName,
        expiryDate: expiryDate,
        cvv: cvv
    };

    // Si la validación es exitosa, enviar la petición AJAX
    $.ajax({
        url: '/update-vip',
        method: 'POST',
        data: {
            cardInfo: cardInfo,
            _token: token,
        },
        success: function(data) {
            if (data.success) {
                localStorage.setItem('showAlertUpdateVip', 'true'); // Guardar en el localstorage
                location.reload();
            } else {
                alert('There was an error updating the user.');
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error('Error details:', textStatus, errorThrown, jqXHR.responseText);
            alert('An error occurred: ' + textStatus + ' - ' + errorThrown + '\n' + jqXHR.responseText);
        }
    });
});

if(btnCancelVip){
    btnCancelVip.addEventListener('click', function() {
        $('#passwordModal').modal('show');
    });

    document.getElementById('passwordForm').addEventListener('submit', function(event) {
        event.preventDefault();

        let password = document.getElementById('password').value;

        $.ajax({
            url: '/cancel-vip',
            type: 'POST',
            data: { 
                password: password,
                _token: token
            },
            success: function(response) {
                if (response.success) {
                    localStorage.setItem('showAlertCancelVip', 'true'); //guardar en el localstorage
                    location.reload();
                } else {
                    document.getElementById('passwordError').innerText = response.error;
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('An error occurred: ' + textStatus + ' - ' + errorThrown);
            }
        });
    });
}

window.onload = function() {
    if (localStorage.getItem('showAlertCancelVip') === 'true') {
        let alertDiv = document.getElementById('alertCancelVip');
        alertDiv.style.display = 'block';
        document.getElementById('alert-messageCancelVip').textContent = 'You canceled the subscription';

        setTimeout(function() {
            $(alertDiv).fadeOut(1000, function() {
                $(this).css('display', 'none');
            });
        }, 3000);

        localStorage.removeItem('showAlertCancelVip'); // Corrected key
    }
    if (localStorage.getItem('showAlertUpdateVip') === 'true') {
        let alertDiv = document.getElementById('alertUpdateVip');
        alertDiv.style.display = 'block';
        document.getElementById('alert-messageUpdateVip').textContent = 'User updated successfully.';

        setTimeout(function() {
            $(alertDiv).fadeOut(1000, function() {
                $(this).css('display', 'none');
            });
        }, 3000);

        localStorage.removeItem('showAlertUpdateVip'); // Corrected key
    }
}
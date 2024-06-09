// Get the cancel VIP button element and the CSRF token from the meta tag
let btnCancelVip = document.getElementById('cancelVip');
let token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

export function openCard(btn){
// Toggle card details visibility when the 'buyVip' button is clicked
$('#'+btn).click(function() {
    $('#cardDetails').toggle();
});

// Automatically move focus to the next input field after 4 digits in card number inputs
$('#cardNumber1, #cardNumber2, #cardNumber3, #cardNumber4').keyup(function() {
    if (this.value.length == this.maxLength) {
        $(this).nextAll('.form-control').first().focus();
    }
});
}
openCard("buyVip");

// Handle form submission for payment
$('#submitPayment').click(function(e) {
    e.preventDefault(); // Prevent the default form submission
    let cardInfo = payForm();

    if(!cardInfo){
        return;
    }

    // If validation is successful, send an AJAX request to update VIP status
    $.ajax({
        url: '/update-vip',
        method: 'POST',
        data: {
            cardInfo: cardInfo,
            _token: token,
        },
        success: function(data) {
            if (data.success) {
                localStorage.setItem('showAlertUpdateVip', 'true'); // Save a flag in local storage
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

export function payForm(){
    // Validate input fields
    let cardNumber = $('#cardNumber1').val() + $('#cardNumber2').val() + $('#cardNumber3').val() + $('#cardNumber4').val();
    let cardName = $('#cardName').val();
    let expiryDate = $('#expiryDate').val();
    let cvv = $('#cvv').val();
    let errorMessage = '';
    let isValid = true;

    // Validate card number
    if (!/^\d{16}$/.test(cardNumber)) {
        errorMessage += 'Please enter a valid 16 digit card number.<br>';
        isValid = false;
    }
    
    // Validate card name
    if (!/^[a-zA-Z\s]+$/.test(cardName)) {
        errorMessage += 'Please enter a valid card name.<br>';
        isValid = false;
    }
    
    // Validate expiry date
    if (!/^(0[1-9]|1[0-2])\/\d{2}$/.test(expiryDate)) {
        errorMessage += 'Please enter a valid expiry date in the format MM/YY.<br>';
        isValid = false;
    } else {
        let today = new Date();
        let currentYear = today.getFullYear().toString().substr(-2);
        let currentMonth = ('0' + (today.getMonth() + 1)).slice(-2);
    
        let [expiryMonth, expiryYear] = expiryDate.split('/');
    
        if (expiryYear < currentYear || (expiryYear == currentYear && expiryMonth < currentMonth)) {
            errorMessage += 'Expiry date cannot be in the past.<br>';
            isValid = false;
        }
    }
    
    // Validate CVV
    if (!/^\d{3}$/.test(cvv)) {
        errorMessage += 'Please enter a valid 3 digit CVV.<br>';
        isValid = false;
    }
    
    // If there are validation errors, display them and stop the submission
    if (errorMessage) {
        document.getElementById('errorMessage').innerHTML = errorMessage;
    }

    // Prepare card information for submission
    let cardInfo = {
        cardNumber: cardNumber,
        cardName: cardName,
        expiryDate: expiryDate,
        cvv: cvv
    };

    return isValid ? cardInfo : null;
}
// Handle cancel VIP button click and show password modal
if(btnCancelVip){
    btnCancelVip.addEventListener('click', function() {
        $('#passwordModal').modal('show');
    });

    // Handle password form submission for canceling VIP
    document.getElementById('passwordForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent default form submission

        let password = document.getElementById('password').value;

        // Send an AJAX request to cancel VIP status
        $.ajax({
            url: '/cancel-vip',
            type: 'POST',
            data: { 
                password: password,
                _token: token
            },
            success: function(response) {
                if (response.success) {
                    localStorage.setItem('showAlertCancelVip', 'true'); // Save a flag in local storage
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

// Show alerts if needed when the page loads
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

        localStorage.removeItem('showAlertCancelVip'); // Remove the flag from local storage
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

        localStorage.removeItem('showAlertUpdateVip'); // Remove the flag from local storage
    }
}

// Get the cancel VIP button element and the CSRF token from the meta tag
const btnCancelVip = document.getElementById('cancelVip');
const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// Function to toggle card details visibility
export function openCard(btn) {
    try {
        $('#' + btn).click(() => {
            $('#cardDetails').toggle();
        });

        // Automatically move focus to the next input field after 4 digits in card number inputs
        $('#cardNumber1, #cardNumber2, #cardNumber3, #cardNumber4').keyup(function() {
            if (this.value.length === this.maxLength) {
                $(this).nextAll('.form-control').first().focus();
            }
        });
    } catch (error) {
        console.error('Error in openCard function:', error);
    }
}
openCard("buyVip");

// Handle form submission for payment
$('#submitPayment').click(function(e) {
    e.preventDefault(); // Prevent the default form submission

    const cardInfo = payForm();
    if (!cardInfo) {
        return;
    }

    // If validation is successful, send an AJAX request to update VIP status
    try {
        $.ajax({
            url: '/update-vip',
            method: 'POST',
            data: {
                cardInfo: cardInfo,
                _token: token,
            },
            success: (data) => {
                if (data.success) {
                    setLocalStorageItem('showAlertUpdateVip', 'true');
                    location.reload();
                } else {
                    alert('There was an error updating the user.');
                }
            },
            error: handleError
        });
    } catch (error) {
        console.error('Error in submitPayment click handler:', error);
    }
});

// Function to validate payment form inputs
export function payForm() {
    try {
        const cardNumber = $('#cardNumber1').val() + $('#cardNumber2').val() + $('#cardNumber3').val() + $('#cardNumber4').val();
        const cardName = $('#cardName').val();
        const expiryDate = $('#expiryDate').val();
        const cvv = $('#cvv').val();
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
            const today = new Date();
            const currentYear = today.getFullYear().toString().substr(-2);
            const currentMonth = ('0' + (today.getMonth() + 1)).slice(-2);

            const [expiryMonth, expiryYear] = expiryDate.split('/');

            if (expiryYear < currentYear || (expiryYear === currentYear && expiryMonth < currentMonth)) {
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
            return null;
        }

        // Prepare card information for submission
        return {
            cardNumber: cardNumber,
            cardName: cardName,
            expiryDate: expiryDate,
            cvv: cvv
        };
    } catch (error) {
        console.error('Error in payForm function:', error);
        return null;
    }
}

// Handle cancel VIP button click and show password modal
if (btnCancelVip) {
    btnCancelVip.addEventListener('click', () => {
        $('#passwordModal').modal('show');
    });

    // Handle password form submission for canceling VIP
    document.getElementById('passwordForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent default form submission

        const password = document.getElementById('password').value;

        // Send an AJAX request to cancel VIP status
        try {
            $.ajax({
                url: '/cancel-vip',
                type: 'POST',
                data: {
                    password: password,
                    _token: token
                },
                success: (response) => {
                    if (response.success) {
                        setLocalStorageItem('showAlertCancelVip', 'true');
                        location.reload();
                    } else {
                        document.getElementById('passwordError').innerText = response.error;
                    }
                },
                error: handleError
            });
        } catch (error) {
            console.error('Error in passwordForm submit handler:', error);
        }
    });
}

// Function to set item in local storage
function setLocalStorageItem(key, value) {
    try {
        localStorage.setItem(key, value);
    } catch (error) {
        console.error('Error setting localStorage item:', error);
    }
}

// Function to remove item from local storage
function removeLocalStorageItem(key) {
    try {
        localStorage.removeItem(key);
    } catch (error) {
        console.error('Error removing localStorage item:', error);
    }
}

// Function to handle AJAX errors
function handleError(jqXHR, textStatus, errorThrown) {
    console.error('Error details:', textStatus, errorThrown, jqXHR.responseText);
    alert('An error occurred: ' + textStatus + ' - ' + errorThrown + '\n' + jqXHR.responseText);
}

// Show alerts if needed when the page loads
window.onload = function() {
    try {
        if (localStorage.getItem('showAlertCancelVip') === 'true') {
            displayAlert('alertCancelVip', 'alert-messageCancelVip', 'You canceled the subscription');
            removeLocalStorageItem('showAlertCancelVip');
        }
        if (localStorage.getItem('showAlertUpdateVip') === 'true') {
            displayAlert('alertUpdateVip', 'alert-messageUpdateVip', 'User updated successfully.');
            removeLocalStorageItem('showAlertUpdateVip');
        }
    } catch (error) {
        console.error('Error in window onload handler:', error);
    }
}

// Function to display alert messages
function displayAlert(alertId, messageId, message) {
    try {
        const alertDiv = document.getElementById(alertId);
        alertDiv.style.display = 'block';
        document.getElementById(messageId).textContent = message;

        setTimeout(() => {
            $(alertDiv).fadeOut(1000, function() {
                $(this).css('display', 'none');
            });
        }, 3000);
    } catch (error) {
        console.error('Error in displayAlert function:', error);
    }
}
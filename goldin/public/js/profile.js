$(document).ready(function() {
    $('#buyVip').click(function() {
        $('#cardDetails').toggle();
    });
    // Automatically move focus to the next input field after 4 digits
    $('#cardNumber1, #cardNumber2, #cardNumber3').keyup(function() {
        if (this.value.length == this.maxLength) {
            $(this).next('.form-control').focus();
        }
    });
});

let btnCancelVip = document.getElementById('cancelVip');

if(btnCancelVip){
    document.getElementById('cancelVip').addEventListener('click', function() {
        $('#passwordModal').modal('show');
    });

    document.getElementById('passwordForm').addEventListener('submit', function(event) {
        event.preventDefault();

        var password = document.getElementById('password').value;
        var token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        $.ajax({
            url: '/cancel-vip',
            type: 'POST',
            data: { 
                password: password,
                _token: token
            },
            success: function(response) {
                if (response.success) {
                    localStorage.setItem('showAlertVip', 'true'); //guardar en el localstorage
                    location.reload();
                } else {
                    document.getElementById('passwordError').innerText = response.error;
                }
            },
            error: function(response) {
                if (response.status === 422) {
                    var errors = $.parseJSON(response.responseText);
                    $.each(errors.errors, function (key, val) {
                        $(".text-red-600").text(val[0]);
                    });
                } else {
                    alert('An error occurred: ' + xhr.status);
                }
            }
        });
    });
}

window.onload = function() {
    if (localStorage.getItem('showAlertVip') === 'true') {
        var alertDiv = document.getElementById('alertCancelVip');
        alertDiv.style.display = 'block';
        document.getElementById('alert-message').textContent = 'You canceled the subscription';

        setTimeout(function() {
            $(alertDiv).fadeOut(1000, function() {
                $(this).css('display', 'none');
            });
        }, 3000);

        localStorage.removeItem('showAlertVip');
    }
}
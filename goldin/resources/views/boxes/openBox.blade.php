@extends('layouts.case-layout')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div id="alertCoins" class="alert alert-danger alert-dismissible fade show m-3 position-relative hideCard" role="alert">
                    <span id="alert-messageCoins"></span>
                </div>
                <div class="d-flex justify-content-center mt-3">
                    <div id="weapon-container" class="d-flex justify-content-center align-items-center showclothes" aria-label="Weapon container">
                        <img class="weapon-image animate__animated" src="{{ asset('images/random_weapon.png') }}" alt="weapon" width="175" height="175">
                    </div>
                </div>
                @foreach ($boxes as $box)
                    <div class="d-flex justify-content-center mt-3">
                        <button id="openBoxButtonDaily" type="button" data-timer="{{ $timer }}" {{ $canOpenBox ? '' : 'disabled' }} class='align-items-center inline-flex items-center px-6 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-sm text-black dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150'>
                            @if($canOpenBox)
                                Open
                            @else
                                @if($timer)
                                    Next Open Time: {{ $timer }}
                                @else
                                    Needs Level {{ $box->level }} And Be <span class="text-danger">&nbsp;VIP&nbsp;</span> To Open
                                @endif
                            @endif
                        </button>
                    </div>
                    <div id="weapon-message" class="d-flex justify-content-center mt-3 text-white display-6" aria-label="Weapon message"></div>
                    <div class="d-flex justify-content-center mt-3 flex-wrap" aria-label="Coins section">
                        <div class="mx-2 mb-3 dark:bg-gray-800 d-flex flex-column justify-content-between text-white weaponborders">
                            <div class="mx-2 mb-3 dark:bg-gray-800 d-flex flex-column justify-content-between text-white">
                                <img src="{{ asset('images/user_coin.png') }}" alt="coins" title="coins" width="235" height="235" aria-label="Coins image">
                                <div class="text-center" aria-label="Coins text">
                                    <p>Coins: {{ 2 * $box->level }} - {{ 10 * $box->level }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            try {
                // Call updateTimeRemaining once to initialize the remaining time
                updateTimeRemaining();
                
                // Set an interval to update the remaining time every second
                setInterval(updateTimeRemaining, 1000);

                // Event listener to open the daily box
                $('#openBoxButtonDaily').click(function() {
                    $.ajax({
                        url: '{{ route("ajaxDailyOpenBox") }}',
                        method: 'POST',
                        data: {
                            box_name: '{{ $boxes->first()->box_name }}',
                            _token: '{{ csrf_token() }}'
                        },
                        dataType: 'json',
                        success: function(data) {
                            try {
                                if (data.error) {
                                    console.error(data.error);
                                    alert(data.error);
                                } else {
                                    $('#openBoxButtonDaily').prop('disabled', true);
                                    $('#openBoxButtonDaily').html('You can open the box in <span class="text-danger">&nbsp;24h&nbsp;</span>');
                                    console.log(data);
                                    $('#coins').text(data.totalCoins);
                                    let color = "#FFFF00";
                                    $('#weapon-container').animate({
                                        backgroundColor: color,
                                        borderColor: color
                                    }, 600);
                                    let newImage = new Image();
                                    newImage.src = '{{ asset('images/user_coin.png') }}';
                                    newImage.onload = function() {
                                        $('.weapon-image').addClass('fadeOut').one('animationend', function() {
                                            $(this).css('display', 'none');
                                            $('.weapon-image').attr('src', newImage.src).css({width: '300px', height: 'auto'});
                                            $('.weapon-image').css('display', '');
                                            $('.weapon-image').removeClass('fadeOut').addClass('fadeIn').one('animationend', function() {
                                                $(this).removeClass('fadeIn');
                                            });
                                        });
                                    }
                                    $('#weapon-message').html('<p>You have obtained <span class="text-success">' + data.coinsWon + '</span> coins</p>');
                                }
                            } catch (error) {
                                console.error('Error in AJAX success handler:', error);
                                alert('An error occurred during processing the response.');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error:', error);
                            console.log('Status:', status);
                            console.log('XHR:', xhr);
                            // Show the alert
                            try {
                                var alertDiv = document.getElementById('alertCoins');
                                alertDiv.style.display = 'block';
                                document.getElementById('alert-messageCoins').textContent = xhr.responseJSON.error;

                                setTimeout(function() {
                                    $(alertDiv).fadeOut(1000, function() {
                                        $(this).css('display', 'none');
                                    });
                                }, 3000);
                            } catch (error) {
                                console.error('Error in AJAX error handler:', error);
                                alert('An error occurred during handling the AJAX error.');
                            }
                        }
                    });
                });
            } catch (error) {
                console.error('Error on document ready:', error);
                alert('An error occurred while initializing the document.');
            }
        });

        // Function to update the remaining time on the button
        function updateTimeRemaining() {
            let timerText = $('#openBoxButtonDaily').data('timer');
            if (timerText) {
                let timeParts = timerText.split(' ');
                let hours = parseInt(timeParts[0]);
                let minutes = parseInt(timeParts[1]);
                let seconds = parseInt(timeParts[2]);

                // Subtract 1 second from the remaining time
                seconds--;
                if (seconds < 0) {
                    seconds = 59;
                    minutes--;
                    if (minutes < 0) {
                        minutes = 59;
                        hours--;
                    }
                }

                // Format minutes and seconds to always have 2 digits
                let formattedHours = hours < 10 ? '0' + hours : hours;
                let formattedMinutes = minutes < 10 ? '0' + minutes : minutes;
                let formattedSeconds = seconds < 10 ? '0' + seconds : seconds;

                // Update the button text with the remaining time
                $('#openBoxButtonDaily').data('timer', formattedHours + ' ' + formattedMinutes + ' ' + formattedSeconds);
                $('#openBoxButtonDaily').html('Next Open Time: <span class="text-danger">' + formattedHours + 'H ' + formattedMinutes + 'M ' + formattedSeconds + 'S</span>');
            }
        }
    </script>
@endsection
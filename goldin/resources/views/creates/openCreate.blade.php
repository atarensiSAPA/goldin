@extends('layouts.case-layout')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div id="alertCoins" class="alert alert-danger alert-dismissible fade show m-3 position-relative hideCard" role="alert">
                    <span id="alert-messageCoins"></span>
                </div>
                <div class="d-flex justify-content-center mt-3">
                    <div id="weapon-container" class="d-flex justify-content-center align-items-center showWeapons">
                        <img class="weapon-image animate__animated" src="{{ asset('images/random_weapon.png') }}" alt="weapon" width="175" height="175">
                    </div>
                </div>
                @foreach ($creates as $create)
                    <div class="d-flex justify-content-center mt-3">
                        @if ($create->daily)
                            <button id="openBoxButtonDaily" type="button" {{ $canOpenBox ? '' : 'disabled' }} class='align-items-center inline-flex items-center px-6 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-sm text-black dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150'>
                                @if($canOpenBox)
                                    Open
                                @elseif($timer)
                                    You can open the box in <span class="text-danger">&nbsp;{{ $timer }}&nbsp;</span>
                                @else
                                    Needs Level {{ $create->level }} And Be <span class="text-danger">&nbsp;VIP&nbsp;</span> To Open
                                @endif
                            </button>
                        @else
                            <button id="openBoxButton" type="button" class='align-items-center inline-flex items-center px-6 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-sm text-black dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150'>
                                Open Create For {{ $creates->firstWhere('cost', '!=', null)->cost ?? 'N/A' }}<img src="{{ asset('images/user_coin.png') }}" alt="coin" width="30" height="30">
                            </button>
                        @endif
                    </div>
                    <div id="weapon-message" class="d-flex justify-content-center mt-3 text-white display-6"></div>
                    <div class="d-flex justify-content-center mt-3 flex-wrap">
                        @if ($create->daily)
                            <div class="mx-2 mb-3 dark:bg-gray-800 d-flex flex-column justify-content-between text-white weaponborders">
                                <div class="mx-2 mb-3 dark:bg-gray-800 d-flex flex-column justify-content-between text-white">
                                    <img src="{{ asset('images/user_coin.png') }}" alt="coins" title="coins" width="235" height="235">
                                    <div class="text-center">
                                        <p>Coins: {{ 2 * $create->level }} - {{ 10 * $create->level }}</p>
                                    </div>
                                </div>
                            </div>
                        @else
                            @foreach ($create->weapons as $weapon)
                                @if ($weapon && $weapon->units > 0)
                                    <div class="mx-2 mb-3 dark:bg-gray-800 d-flex flex-column justify-content-between text-white weaponborders borderWeapon" style="border: 3px solid {{ $weapon->color }};">
                                        <img src="{{ asset('images/skins/' . $weapon->weapon_img) }}" alt="{{ $weapon->weapon_name }}" title="{{ $weapon->weapon_name }}" width="235" height="235">
                                        <div>
                                            <p>Weapon Name: {{ $weapon->weapon_name }}</p>
                                            <p>Skin Name: {{ str_replace('_', ' ', $weapon->weapon_skin) }}</p>
                                            <p class="d-flex align-items-center">Price: {{ $weapon->price }}<img src="{{ asset('images/user_coin.png') }}" alt="coin" width="30" height="30"></p>
                                            <p>Chance: {{ $weapon->appearance_percentage }}%</p>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#openBoxButtonDaily').click(function() {
                $.ajax({
                    url: '{{ route("ajaxDailyOpenBox") }}',
                    method: 'POST',
                    data: {
                        box_name: '{{ $creates->first()->box_name }}',
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: 'json',
                    success: function(data) {
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
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                        console.log('Status:', status);
                        console.log('XHR:', xhr);
                        // Show the alert
                        var alertDiv = document.getElementById('alertCoins');
                        alertDiv.style.display = 'block';
                        document.getElementById('alert-messageCoins').textContent = xhr.responseJSON.error;

                        setTimeout(function() {
                            $(alertDiv).fadeOut(1000, function() {
                                $(this).css('display', 'none');
                            });
                        }, 3000);
                    }
                });
            });

            $('#openBoxButton').click(function() {
                $.ajax({
                    url: '{{ route("ajaxOpenBox") }}',
                    method: 'POST',
                    data: {
                        box_name: '{{ $creates->first()->box_name }}',
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data.error) {
                            console.error(data.error);
                            alert(data.error);
                        } else {
                            console.log(data);
                            $('#coins').text(data.coins);
                            let color = tinycolor(data.color).lighten(20).toString();
                            $('#weapon-container').animate({
                                backgroundColor: color,
                                borderColor: data.color
                            }, 600);
                            let newImage = new Image();
                            newImage.src = '{{ asset('images/skins') }}/' + data.weapon.weapon_img;
                            newImage.onload = function() {
                                $('.weapon-image').addClass('fadeOut').one('animationend', function() {
                                    $(this).css('display', 'none');
                                    $('.weapon-image').attr('src', newImage.src).css({width: '400px', height: 'auto'});
                                    $('.weapon-image').css('display', '');
                                    $('.weapon-image').removeClass('fadeOut').addClass('fadeIn').one('animationend', function() {
                                        $(this).removeClass('fadeIn');
                                    });
                                });
                            }
                            $('#weapon-message').html('<p>You have obtained a <span style="color:' + color + ';"> ' + ' ' +data.weapon.rarity.toUpperCase() + '</span> weapon</p>');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                        console.log('Status:', status);
                        console.log('XHR:', xhr);
                        // Show the alert
                        var alertDiv = document.getElementById('alertCoins');
                        alertDiv.style.display = 'block';
                        document.getElementById('alert-messageCoins').textContent = xhr.responseJSON.error;

                        setTimeout(function() {
                            $(alertDiv).fadeOut(1000, function() {
                                $(this).css('display', 'none');
                            });
                        }, 3000);
                    }
                });
            });
        });
    </script>

@endsection

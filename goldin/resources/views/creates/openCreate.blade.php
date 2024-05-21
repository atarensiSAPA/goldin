@extends('layouts.case')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="d-flex justify-content-center" style="margin-top: 20px;">
                    <div id="weapon-container" class="d-flex justify-content-center align-items-center" style="width: 600px; height: 300px; background: linear-gradient(rgba(0,0,0,0.8), rgba(0,0,0,0.8)); border-radius: 15px; border: 2px solid white;">
                        <img class="weapon-image animate__animated" src="{{ asset('images/random_weapon.png') }}" alt="weapon" width="175" height="175">
                    </div>
                </div>
                <div class="d-flex justify-content-center mt-3">
                    <button id="openBoxButton" type="button" class='inline-flex items-center px-6 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-sm text-black dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150'>
                        Open Create For {{ $creates->firstWhere('cost', '!=', null)->cost ?? 'N/A' }}<img src="{{ asset('images/user_coin.png') }}" alt="coin" width="30" height="30">
                    </button>
                </div>
                <div id="weapon-message" class="d-flex justify-content-center mt-3" style="font-size: 24px; color: white;"></div>
                <div class="d-flex justify-content-center mt-3 flex-wrap">
                    @foreach ($creates as $create)
                        @foreach ($create->weapons as $weapon)
                            @if ($weapon && $weapon->units > 0)
                                <div class="mx-2 mb-3 dark:bg-gray-800 d-flex flex-column justify-content-between" style="border: 3px solid {{ $weapon->color }}; border-radius: 15px; padding: 10px 10px 5px 10px; color:white;">
                                    <img src="{{ asset('images/skins/' . $weapon->weapon_img) }}" alt="{{ $weapon->weapon_name }}" title="{{ $weapon->weapon_name }}" width="235" height="235">
                                    <div>
                                        <p>Weapon Name: {{ $weapon->weapon_name }}</p>
                                        <p>Skin Name: {{ str_replace('_', ' ', $weapon->weapon_skin) }}</p>
                                        <p>€{{ $weapon->price }}</p>
                                        <p>Chance: {{ $weapon->appearance_percentage }}%</p>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#openBoxButton').click(function() {
                $.ajax({
                    url: '/ajaxOpenBox',
                    method: 'POST',
                    data: {
                        box_name: '{{ $creates->first()->box_name }}',
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data.error) {
                            alert(data.error);
                        } else {
                            console.log(data);
                            $('#coins').text(data.coins);
                            var color = tinycolor(data.color).lighten(20).toString();
                            $('#weapon-container').animate({
                                backgroundColor: color,
                                borderColor: data.color
                            }, 600);
                            var newImage = new Image();
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
                });
            });
        });
    </script>

@endsection

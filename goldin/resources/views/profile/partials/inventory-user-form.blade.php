<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            Inventory
        </h2>
        <div class="mt-2">
            <label for="filter" class="block text-sm font-medium text-gray-700" style="color:white">Filter by:</label>
            <select id="filter" name="filter" class="inline-flex items-center px-3 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-sm text-black dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 mt-1 block w-60">
                <option value="obtention">Order of Obtention</option>
                <option value="price">Price</option>
                <option value="rarity">Rarity</option>
            </select>
        </div>
    </header>

    <div class="mt-4">
        <div class="d-flex justify-content-center mt-3 flex-wrap">
            @foreach ($weapons as $weapon)
                @if ($weapon)
                    <div class="d-flex justify-content-center mt-3 flex-wrap">
                        <div id="weapon-container-{{ $weapon->id }}" class="mx-2 mb-3 dark:bg-gray-800 d-flex flex-column justify-content-between weapon-container" style="border: 3px solid {{ $weapon->color }}; border-radius: 15px; padding: 10px 10px 5px 10px; color:white;">
                            <img src="{{ asset('images/skins/' . $weapon->weapon_img) }}" alt="{{ $weapon->name }}" title="{{ $weapon->name }}" width="235" height="235">
                            <div class="mt-auto">
                                <p>Weapon Name: {{ $weapon->weapon_name }}</p>
                                <p>Skin Name: {{ str_replace('_', ' ', $weapon->weapon_skin) }}</p>
                                <p class="d-flex align-items-center">Price: {{ $weapon->price }}<img src="{{ asset('images/user_coin.png') }}" alt="coin" width="30" height="30"></p>
                            </div>
                            <div class="d-flex justify-content-center weapon-buttons">
                                <button type="button" class="btn btn-success sell-button me-3" data-weapon-id="{{ $weapon->id }}">Vender</button>
                                <button type="button" class="btn btn-primary">Retirar</button>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
    
    <script>
        $(document).on('click', '.sell-button', function() {
            var weaponId = $(this).data('weaponId');
    
            if (confirm('¿Estás seguro de que quieres vender esta arma?')) {
                $.ajax({
                    url: '/sell-weapon',
                    method: 'POST',
                    data: {
                        weapon_id: weaponId,
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data.success) {
                            alert('Arma vendida con éxito. El dinero ha sido añadido a tu cuenta.');
                            location.reload();
                        } else {
                            alert('Hubo un error al vender la arma. Por favor, inténtalo de nuevo.');
                        }
                    }
                });
            }
        });
    
        $('#filter').change(function() {
            var filter = $(this).val();
            var page = $(this).data('page') || 1;

            $.ajax({
                url: '/filter-weapons',
                method: 'POST',
                data: {
                    filter: filter,
                    _token: '{{ csrf_token() }}'
                },
                dataType: 'json',
                success: function(data) {
                    var weaponsContainer = $('.d-flex.justify-content-center.mt-3.flex-wrap');
                    weaponsContainer.empty();

                    data.weapons.forEach(function(weapon) {
                        var weaponDiv = $('<div>').addClass('mx-2 mb-3 dark:bg-gray-800 d-flex flex-column justify-content-between weapon-container')
                            .css({
                                'border': '3px solid ' + weapon.color,
                                'border-radius': '15px',
                                'padding': '10px 10px 5px 10px',
                                'color': 'white'
                            })
                            .attr('id', 'weapon-container-' + weapon.id);

                        var img = $('<img>').attr({
                            'src': '/images/skins/' + weapon.weapon_img,
                            'alt': weapon.name,
                            'title': weapon.name,
                            'width': '235',
                            'height': '235'
                        });

                        var weaponInfo = $('<div>').addClass('mt-auto')
                            .append('<p>Weapon Name: ' + weapon.weapon_name + '</p>')
                            .append('<p>Skin Name: ' + weapon.weapon_skin.replace('_', ' ') + '</p>')
                            .append('<p class="d-flex align-items-center">Price: ' + weapon.price + '<img src="/images/user_coin.png" alt="coin" width="30" height="30"></p>');

                        var buttons = $('<div>').addClass('d-flex justify-content-center weapon-buttons')
                            .append('<button type="button" class="btn btn-success sell-button me-3" data-weapon-id="' + weapon.id + '">Vender</button>')
                            .append('<button type="button" class="btn btn-primary">Retirar</button>');

                        weaponDiv.append(img, weaponInfo, buttons);
                        weaponsContainer.append(weaponDiv);
                    });
                }
            });
        });
    </script>
</section>
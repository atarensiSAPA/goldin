<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            Inventory
        </h2>
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
        $('.sell-button').click(function() {
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
    </script>
</section>
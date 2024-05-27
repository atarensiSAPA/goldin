<section>
    <div id="alertWeapon" class="alert alert-success alert-dismissible fade show m-3 position-relative hideCard" role="alert">
        <span id="alert-messageWeapon"></span>
    </div>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            Inventory
        </h2>
        @unless ($weapons->isEmpty())
            <div class="mt-2">
                <label for="filter" class="block text-sm font-medium text-gray-700 text-white">Filter by:</label>
                <select id="filter" name="filter" class="inline-flex items-center px-3 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-sm text-black dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 mt-1 block w-60">
                    <option value="obtention">Order of Obtention</option>
                    <option value="price">Price</option>
                    <option value="rarity">Rarity</option>
                </select>
            </div>
        @endunless
    </header>

    <div class="mt-4">
        <div class="d-flex justify-content-center mt-3 flex-wrap">
            @forelse ($weapons as $weapon)
                <div class="d-flex justify-content-center mt-3 flex-wrap">
                    <div id="weapon-container-{{ $weapon->id }}" class="mx-2 mb-3 dark:bg-gray-800 d-flex flex-column justify-content-between weapon-container" style="border-color: {{ $weapon->color }};">
                        <img src="{{ asset('images/skins/' . $weapon->weapon_img) }}" alt="{{ $weapon->name }}" title="{{ $weapon->name }}" width="235" height="235">
                        <div class="mt-auto">
                            <p>Weapon Name: {{ $weapon->weapon_name }}</p>
                            <p>Skin Name: {{ str_replace('_', ' ', $weapon->weapon_skin) }}</p>
                            <p class="d-flex align-items-center">Price: {{ $weapon->price }}<img src="{{ asset('images/user_coin.png') }}" alt="coin" width="30" height="30"></p>
                        </div>
                        <div class="d-flex justify-content-center weapon-buttons">
                            <button type="button" class="btn btn-success sell-button me-3" data-weapon-id="{{ $weapon->id }}">Sell</button>
                            <button type="button" class="btn btn-primary">Withdraw</button>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-white">No tienes armas en tu inventario. ¡Abre cajas para conseguir más!</p>
            @endforelse
        </div>
    </div>

<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content bg-gray-800 text-white">
            <div class="p-6 bg-gray-800">
                <h2 class="text-lg font-medium text-white" id="confirmModalLabel">
                    Confirmation
                </h2>
                
                <p class="mt-1 text-sm text-gray-400">
                    Are you sure you want to sell this weapon?
                </p>

                <div class="mt-6 flex justify-end">
                    <x-secondary-button x-on:click="$dispatch('close')" class="bg-gray-700 text-black" data-dismiss="modal">{{ __('Cancel') }}</x-secondary-button>

                    <button type="button" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 ms-3 bg-red-700 text-white" id="confirmButton">
                        Confirm
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
</section>
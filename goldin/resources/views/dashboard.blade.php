<x-app-layout>
    <div class="container">
        <div id="alertUpdateCart" class="alert alert-success alert-dismissible fade show m-3 position-relative hideCard" role="alert">
            <span id="alert-messageUpdateCart"></span>
        </div>
        <form action="/dashboard" method="GET" class="mt-6 space-y-6">
            <div>
                <x-input-label for="search" :value="__('Search')" />
                <x-text-input id="search" name="search" type="text" class="mt-1 block w-full" required autofocus autocomplete="name" />
                <x-input-error class="mt-2" :messages="$errors->get('search')" />
            </div>
        
            <div class="flex items-center gap-4">
                <x-third-button>{{ __('Search') }}</x-third-button>
                <a href="/dashboard" class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                    {{ __('Search all Clothes') }}
                </a>
            </div>
        </form>
        @php
            $searchTerm = request()->query('search'); // Obtain search form URL
        @endphp
    <div class="row text-white">
        @forelse ($clothes as $c)
            @if (!$searchTerm || stripos($c->name, $searchTerm) !== false)
                <div class="col-md-3 mt-4 d-flex" id="card-{{ $c->name }}">
                    <div class="card bg-transparent text-white d-flex flex-column align-items-center" id="card-{{ $c->name }}">
                        <div tabindex="0" class="clothesDiv card-body position-relative rounded-lg dark:bg-gray-800 d-flex flex-column align-items-center borderClothes" data-bs-toggle="modal" data-bs-target="#clothesModal" data-clothes-name="{{ $c->name }}" data-clothes-type="{{ $c->type }}" data-clothes-price="{{ $c->price }}" data-clothes-units="{{$c->units}}" data-clothes-img="{{ $c->clothes_img }}" data-clothes-id="{{ $c->id }}">
                            <div class="d-flex justify-content-center align-items-center" style="flex-grow: 1;">
                                <img src="{{ asset('images/clothes/' . $c->clothes_img) }}" alt="Image of {{ $c->name }}" class="img-fluid rounded-0">
                            </div>
                            <div class="overlay position-absolute top-0 start-0 pt-2 pl-2 radius-0 h-auto d-flex align-items-center">
                                <b class="fs-5 fw-bold">{{ $c->price }}</b>
                                <img src="{{ asset('images/user_coin.png') }}" alt="coin" width="30" height="30" class="ms-1">
                            </div>
                            <div class="text-center text-white mt-auto">
                                <b>{{ $c->name }}</b>
                                @if($c->units <= 0)
                                <br><b class="text-danger">Sold out</b>
                                @endif
                            </div>
                        </div>
                        <!-- Add to cart button -->
                        <button class="btn btn-primary add-to-cart-button position-absolute top-0 end-0 me-3 mt-1 displayNone" id="add-to-cart-{{ $c->id }}" data-clothes-id="{{ $c->id }}" data-clothes-name="{{ $c->name }}" data-clothes-type="{{ $c->type }}" data-clothes-price="{{ $c->price }}" data-clothes-img="{{ $c->clothes_img }}" data-clothes-units="{{ $c->units }}" {{ $c->units == 0 ? 'disabled' : '' }}>
                            <span aria-hidden="true">🛒</span>
                            <span class="sr-only">Add to cart</span>
                        </button>
                    </div>
                </div>
            @endif
        @empty
            <p>No clothes found.</p>
        @endforelse
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="clothesModal" tabindex="-1" aria-labelledby="clothesModalLabel" aria-hidden="true">
        <div class="modal-dialog text-light">
            <div class="modal-content">
                <div class="modal-header dark:bg-gray-800">
                    <h5 class="modal-title" id="clothesModalLabel">Clothes Description</h5>
                </div>
                <div class="modal-body bg-gray-100 dark:bg-gray-900" id="clothesDescription"></div>
                <div class="modal-footer modal-body bg-gray-100 dark:bg-gray-900">
                    <x-secondary-button x-on:click="$dispatch('close')" class="bg-gray-700 text-black" data-dismiss="modal">{{ __('Cancel') }}</x-secondary-button>
                    <button type="button" class="btn btn-primary btnCart add-to-cart-button" id="addToCartButton">Add to cart</button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

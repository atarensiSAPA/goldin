<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            Last purchases
        </h2>
    </header>
    <form action="/user-profile" method="GET" class="mt-6 space-y-6">
        <div>
            <x-input-label for="search" :value="__('Search')" />
            <x-text-input id="search" name="search" type="text" class="mt-1 block w-full" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('search')" />
        </div>
    
        <div class="flex items-center gap-4">
            <x-third-button>{{ __('Search') }}</x-third-button>
            <a href="/user-profile" class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                {{ __('Search all Clothes') }}
            </a>
        </div>
    </form>
    @php
        $searchTerm = request()->query('search'); // Obtain search form URL
    @endphp
    <div class="mt-4">
        <div class="d-flex justify-content-center mt-3 flex-wrap">
            @forelse ($purchaseHistory as $purchase)
                @if (!$searchTerm || stripos($purchase->clothes->name, $searchTerm) !== false)
                    <div class="d-flex justify-content-center mt-3 flex-wrap text-white">
                        <div id="clothes-container-{{ $purchase->id }}" class="rounded-lg borderClothes mx-2 mb-3 dark:bg-gray-800 d-flex flex-column justify-content-between clothes-container" style="padding: 20px;">
                            <div class="d-flex justify-content-center align-items-center mb-2 mt-2">
                                <img class="img-fluid rounded-0" src="{{ asset('images/clothes/' . $purchase->clothes->clothes_img) }}" alt="{{ $purchase->clothes->name }}" title="{{ $purchase->clothes->name }}" style="width: 200px; height: 200px; object-fit: contain;">
                            </div>
                            <div class="mt-auto ">
                                <p>Clothes Name: {{ $purchase->clothes->name }}</p>
                                <p>Skin Name: {{ str_replace('_', ' ', $purchase->clothes->type) }}</p>
                                <p>Quantity: {{ $purchase->quantity }}</p>
                                <p class="inline-flex d-flex align-items-center img-fluid rounded-0">Total Spent: {{ $purchase->price * $purchase->quantity }}<img src="{{ asset('images/user_coin.png') }}" alt="coin" width="30" height="30"></p>
                                <p class="time-purchased" data-timestamp="{{ $purchase->formatted_created_at }}">Purchased: {{ $purchase->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>
                @endif
            @empty
                <p class="text-white">You didn't buy any clothes</p>
            @endforelse
        </div>
    </div>
</section>
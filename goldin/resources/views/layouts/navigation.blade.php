<nav x-data="{ open: false, cartOpen: false, cartItems: [] }" class="navbar-dark dark:bg-gray-800 border-b border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <!-- Logo -->
                    <div class="shrink-0 flex items-center">
                        <a href="{{ route('dashboard') }}">
                            <x-application-logo class="block h-9 w-auto fill-current text-gray-200" />
                        </a>
                    </div>
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Shop') }}
                    </x-nav-link>
                    <x-nav-link :href="route('minigames-selector')" :active="request()->routeIs('minigames-selector')">
                        {{ __('Minigames') }}
                    </x-nav-link>
                    <x-nav-link :href="route('daily-boxes')" :active="request()->routeIs('daily-boxes')">
                        {{ __('Daily box') }}
                    </x-nav-link>
                    @if(Auth::user()->role === 2)
                        <x-nav-link :href="route('administrator')" :active="request()->routeIs('administrator')">
                            {{ __('Administrator') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                
                <!-- Show the coins from the user -->
                <div class="hidden sm:flex sm:items-center sm:ml-6 sm:mr-4 d-flex align-items-center">
                    <div class="text-white pr-4">
                        {{ Auth::user()::where('connected', true)->where('role', '!=', 2)->count() }} 👨‍👩‍👧‍👦
                    </div>

                    <div id="coins" class="text-light text-lg font-medium">
                        {{ Auth::user()->coins }}
                    </div>
                    <div class="text-light text-lg font-medium ml-2">
                        <img src="{{ asset('images/user_coin.png') }}" alt="coin" width="30" height="30">
                    </div>
                </div>

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-light hover:text-gray-300 focus:outline-none transition ease-in-out duration-150 bg-dark">
                            <div>{{ Auth::user()->name }}</div>
                    
                            <div class="ml-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 011.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <div class="bg-dark text-light">
                            <x-dropdown-link :href="route('user-profile')">
                                {{ __('Profile') }}
                            </x-dropdown-link>
                    
                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                    
                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </div>
                    </x-slot>
                </x-dropdown>
                <!-- Cart Button -->
                <div class="ml-4 flex items-center">
                    <button type="button" class="btnCartOpener relative inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white hover:text-gray-300 focus:outline-none transition ease-in-out duration-150 bg-dark" data-bs-toggle="modal" data-bs-target="#cartModal">
                        🛒
                        <span x-show="cartItems.length > 0" class="ml-1 inline-block w-5 h-5 text-center bg-red-500 rounded-full text-white text-xs font-semibold" x-text="cartItems.length"></span>
                    </button>
                </div>
            </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-500 hover:text-gray-400 bg-gray-900 focus:outline-none focus:bg-gray-900 focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                <div class="ml-4 flex items-center">
                    <button type="button" class="btnCartOpener relative inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white hover:text-gray-300 focus:outline-none transition ease-in-out duration-150 bg-dark" data-bs-toggle="modal" data-bs-target="#cartModal">
                        🛒
                        <span x-show="cartItems.length > 0" class="ml-1 inline-block w-5 h-5 text-center bg-red-500 rounded-full text-white text-xs font-semibold" x-text="cartItems.length"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Shop') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('minigames-selector')" :active="request()->routeIs('minigames-selector')">
                {{ __('Minigames') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('daily-boxes')" :active="request()->routeIs('daily-boxes')">
                {{ __('Daily box') }}
            </x-responsive-nav-link>
            @if(Auth::user()->role === 2)
                <x-responsive-nav-link :href="route('administrator')" :active="request()->routeIs('administrator')">
                    {{ __('Administrator') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 bg-dark">
            <div class="px-4">
                <div class="font-medium text-base text-light">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-200">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1 bg-dark">
                <x-responsive-nav-link :href="route('user-profile')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>

<!-- Cart Modal -->
<div class="modal fade" id="cartModal" tabindex="-1" aria-labelledby="cartModalLabel" aria-hidden="true">
    <div class="modal-dialog text-white">
        <div class="modal-content">
            <div class="modal-header dark:bg-gray-800">
                <h2 class="modal-title text-lg font-semibold mb-4 " id="cartModalLabel">Cart</h2>
            </div>
            <div class="modal-body bg-gray-100 dark:bg-gray-900 p-4">
                <!-- Aquí se agregarán los elementos del carrito dinámicamente -->
            </div>
            <div class="modal-footer bg-gray-100 dark:bg-gray-900 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')" class="bg-gray-700 text-black" data-dismiss="modal">{{ __('Cancel') }}</x-secondary-button>
                <button type="button" class="btn btn-primary" id="buyCart" onclick="window.location='{{ route('showBuyCart') }}'">Buy</button>
            </div>
        </div>
    </div>
</div>

<!-- Confirm Modal Remove -->
<div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog text-white">
        <div class="modal-content border-4 border-danger">
            <div class="modal-header dark:bg-gray-800">
                <h5 class="modal-title" id="exampleModalLabel">Confirmation</h5>
            </div>
            <div class="modal-body bg-gray-100 dark:bg-gray-900 p-4">
                Are you sure you want to remove this item from the cart?
            </div>
            <div class="modal-footer bg-gray-100 dark:bg-gray-900 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')" class="bg-gray-700 text-black" data-dismiss="modal">{{ __('Cancel') }}</x-secondary-button>
                <button type="button" class="btn btn-primary" id="confirmRemoveButton">Confirm</button>
            </div>
        </div>
    </div>
</div>
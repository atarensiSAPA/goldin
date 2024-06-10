<section>
    <div id="alertCancelVip" class="alert alert-danger alert-dismissible fade show m-3 position-relative hideCard" role="alert">
        <span id="alert-messageCancelVip"></span>
    </div>
    <div id="alertUpdateVip" class="alert alert-success alert-dismissible fade show m-3 position-relative hideCard" role="alert">
        <span id="alert-messageUpdateVip"></span>
    </div>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            Account information
        </h2>
    </header>

    <div class="mt-4">
        <p class="text-lg font-light text-gray-700 dark:text-gray-200">
            Name: {{ $user->name }}
        </p>
        <p class="text-lg font-light text-gray-700 dark:text-gray-200">
            Email: {{ $user->email }}
        </p>
        <p class="text-lg font-light text-gray-700 dark:text-gray-200">
            Role: {{ $roleName }}
        </p>
        <p class="text-lg font-light text-gray-700 dark:text-gray-200">
            @if ($user->role == 0)
                <button id="buyVip" class="btn btn-primary" aria-label="Buy VIP - 4.99€">Buy VIP - 4.99€</button>
            @elseif ($user->role == 1)
                <!-- Mostrar tiempo que le queda en dias, y cuando queden menos de 1 dia en horas -->
                <p class="text-lg font-light text-gray-700 dark:text-gray-200">
                    VIP Subscription: 
                    @if ($user->vip_expires_at)
                        {{ \Carbon\Carbon::parse($user->vip_expires_at)->diffForHumans() }}
                    @else
                        No VIP subscription
                    @endif
                </p>
                <button id="cancelVip" class="btn btn-danger" aria-label="Cancel VIP Subscription">{{ __('Cancel VIP Subscription') }}</button>
            @endif
        </p>

        <!-- Hidden form for card details -->
        <form id="cardDetails" class="mt-4 hideCard" aria-label="Payment Information">
            <div class="form-group row">
                <label for="cardNumber" class="col-sm-2 col-form-label text-white ">Card Number</label>
                <div class="col-sm-10 mb-3">
                    <div class="d-flex align-items-center text-white">
                        <input type="text" class="form-control mr-2" id="cardNumber1" placeholder="####" maxlength="4" style="width: 20%;" required aria-label="First 4 digits of your card number">
                        <span>-</span>
                        <input type="text" class="form-control mr-2" id="cardNumber2" placeholder="####" maxlength="4" style="width: 20%;" required aria-label="Second 4 digits of your card number">
                        <span>-</span>
                        <input type="text" class="form-control mr-2" id="cardNumber3" placeholder="####" maxlength="4" style="width: 20%;" required aria-label="Third 4 digits of your card number">
                        <span>-</span>
                        <input type="text" class="form-control" id="cardNumber4" placeholder="####" maxlength="4" style="width: 20%;" aria-label="Last 4 digits of your card number">
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label for="cardName" class="col-sm-2 col-form-label text-white">Name on Card</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control mb-3" id="cardName" placeholder="Name on Card" required aria-label="Name on your card">
                </div>
            </div>
            <div class="form-group row">
                <label for="expiryDate" class="col-sm-2 col-form-label text-white">Expiry Date</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control mb-3" id="expiryDate" placeholder="MM/YY" required aria-label="Expiry date of your card">
                </div>
            </div>
            <div class="form-group row">
                <label for="cvv" class="col-sm-2 col-form-label text-white">CVV</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control mb-3" id="cvv" placeholder="CVV" maxlength="3" required aria-label="CVV of your card">
                </div>
            </div>
            <div class="mt-2 text-sm text-red-600" id="errorMessage"></div>
            <br><button id="submitPayment" class="btn btn-primary mb-3" aria-label="Submit Payment">Submit Payment</button>
        </form>
        <!-- Modal cancel VIP-->
        <div class="modal fade" id="passwordModal" tabindex="-1" role="dialog" aria-labelledby="passwordModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content bg-gray-800 text-white">
                    <form id="passwordForm" method="post" action="{{ route('cancel-vip') }}" class="p-6 bg-gray-800">
                        @csrf
                        <h2 class="text-lg font-medium text-white">
                            {{ __('Are you sure you want to cancel your VIP subscription?') }}
                        </h2>
        
                        <p class="mt-1 text-sm text-gray-400">
                            {{ __('Once your VIP subscription is cancelled, all of its benefits will be permanently removed. Please enter your password to confirm you would like to cancel your VIP subscription.') }}
                        </p>
        
                        <div class="mt-6">
                            <label for="password" class="sr-only">{{ __('Password') }}</label>
        
                            <input
                                id="password"
                                name="password"
                                type="password"
                                class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm mt-1 block w-full bg-gray-700 text-white"
                                placeholder="{{ __('Password') }}"
                            />
        
                            @error('password')
                                <div class="mt-2 text-sm text-red-600">{{ $message }}</div>
                            @enderror

                            <div id="passwordError" class="mt-2 text-sm text-red-600"></div>
                        </div>
        
                        <div class="mt-6 flex justify-end">
                            <x-secondary-button x-on:click="$dispatch('close')" class="bg-gray-700 text-black" data-dismiss="modal">{{ __('Cancel') }}</x-secondary-button>
        
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 ms-3 bg-red-700 text-white">{{ __('Submit') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <p class="text-lg font-light text-gray-700 dark:text-gray-200 inline-flex d-flex align-items-center" id="profileCoins">
            Coins: {{ $user->coins }} <img src="{{ asset('images/user_coin.png') }}" alt="coin" width="30" height="30" aria-hidden="true">
        </p>
        <p class="text-lg font-light text-gray-700 dark:text-gray-200">
            Level: {{ $user->level }}
        </p>
        <p class="text-lg font-light text-gray-700 dark:text-gray-200">
            Experience: {{ $user->experience }} / {{ $maxExperience }}
        </p>
    </div>
</section>

<section>
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
        <p class="text-lg font-light text-gray-700 dark:text-gray-200 inline-flex d-flex align-items-center">
            Coins: {{ $user->coins }} <img src="{{ asset('images/user_coin.png') }}" alt="coin" width="30" height="30">
        </p>
        <p class="text-lg font-light text-gray-700 dark:text-gray-200">
            Level: {{ $user->level }}
        </p>
        <p class="text-lg font-light text-gray-700 dark:text-gray-200">
            Experience: {{ $user->experience }} / {{ $maxExperience }}
        </p>
    </div>
</section>
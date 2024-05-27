<section class="space-y-6 text-white">
    <div class="max-w-5xl">
        <header>
            Users:
        </header>
        <div>
            <ul>
                <form action="/admin-users" method="GET" class="mt-6 space-y-6">
                    <div>
                        <x-input-label for="search" :value="__('Search')" />
                        <x-text-input id="search" name="search" type="text" class="mt-1 block w-full" required autofocus autocomplete="name" />
                        <x-input-error class="mt-2" :messages="$errors->get('search')" />
                    </div>
            
                    <div class="flex items-center gap-4">
                        <x-third-button>{{ __('Search') }}</x-third-button>
                    </div>
                </form>
            </ul>

            <ul>
                @foreach($nonAdminUsers as $user)
                    <div class="row align-items-start">
                        <div class="col text-left">
                            - {{ $user->name }} ({{ $user->email }})
                            <button class="btn btn-warning">
                                Modify
                            </button>
                            <button class="btn btn-danger">
                                Delete
                            </button>
                            @if($user->connected)
                                <button class="btn btn-secondary">
                                    Kick
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </ul>
        </div>
    </div>
</section>
<section class="space-y-6 text-white">
    <div class="max-w-5xl">
        <header>
            Users:
        </header>
        <div>
            <ul>
                <li>
                    <form action="/admin-users" method="GET" class="mt-6 space-y-6">
                        <div>
                            <x-input-label for="search" :value="__('Search')" />
                            <x-text-input id="search" name="search" type="text" class="mt-1 block w-full" required autofocus autocomplete="name" />
                            <x-input-error class="mt-2" :messages="$errors->get('search')" />
                        </div>
                    
                        <div class="flex items-center gap-4">
                            <x-third-button>{{ __('Search') }}</x-third-button>
                            <a href="/admin-users" class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                                {{ __('Search all users') }}
                            </a>
                        </div>
                    </form>
                </li>
            </ul>
            <ul>
                @forelse($nonAdminUsers as $user)
                    <li class="list-group-item dark:bg-gray-800 text-white d-flex justify-content-between align-items-center w-1/2 rounded-lg">
                        {{ $user->name }} ({{ $user->email }})
                        <div>
                            <button type="button" class="btn btn-primary mr-2" data-bs-toggle="modal" data-bs-target="#editModal" data-userid="{{ $user->id }}" data-username="{{ $user->name }}" data-useremail="{{ $user->email }}" data-userrole="{{ $user->role }}" data-userlevel="{{ $user->level }}" data-usercoins="{{ $user->coins }}">Modify</button>
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmModal" data-userid="{{ $user->id }}">Delete</button>
                            @if($user->connected)
                                <form action="{{ route('users.kick', $user) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-warning">Kick</button>
                                </form>
                            @endif
                        </div>
                    </li>
                @empty
                    <p class="text-center text-danger">No hay usuarios</p>
                @endforelse
            </ul>
        </div>
    </div>
</section>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content bg-gray-800 text-white">
            <section class="p-6 bg-gray-800">
                <header>
                    <h2 class="text-lg font-medium text-white" id="editModalLabel">
                        Edit User
                    </h2>
                </header>

                <form id="editForm" action="" method="POST" class="mt-6 space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <x-input-label for="username" :value="__('Name')" />
                        <x-text-input id="username" name="name" type="text" class="mt-1 block w-full bg-gray-800 text-white" required autofocus autocomplete="name" />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </div>

                    <div>
                        <x-input-label for="useremail" :value="__('Email')" />
                        <x-text-input id="useremail" name="email" type="email" class="mt-1 block w-full bg-gray-800 text-white" required autocomplete="username" />
                        <x-input-error class="mt-2" :messages="$errors->get('email')" />
                    </div>

                    <div>
                        <x-input-label for="role" :value="__('Role')" />
                        <select id="role" name="role" class="mt-1 block w-full bg-gray-800 text-white" required>
                            <option value="0">User</option>
                            <option value="1">VIP</option>
                            <option value="2">Admin</option>
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('role')" />
                    </div>

                    <div>
                        <x-input-label for="level" :value="__('Level')" />
                        <x-text-input id="level" name="level" type="number" class="mt-1 block w-full bg-gray-800 text-white" required />
                        <x-input-error class="mt-2" :messages="$errors->get('level')" />
                    </div>

                    <div>
                        <x-input-label for="coins" :value="__('Coins')" />
                        <x-text-input id="coins" name="coins" type="number" class="mt-1 block w-full bg-gray-800 text-white" required />
                        <x-input-error class="mt-2" :messages="$errors->get('coins')" />
                    </div>

                    <div class="flex items-center gap-4">
                        <x-third-button>{{ __('Save') }}</x-third-button>
                    </div>
                </form>
            </section>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content bg-gray-800 text-white">
            <section class="p-6 bg-gray-800">
                <header>
                    <h2 class="text-lg font-medium text-white" id="confirmModalLabel">
                        Confirmation
                    </h2>

                    <p class="mt-1 text-sm text-gray-400">
                        Are you sure you want to delete this user?
                    </p>
                </header>

                <form id="deleteForm" action="" method="POST" class="mt-6 space-y-6">
                    @csrf
                    @method('DELETE')

                    <div class="flex items-center gap-4">
                        <x-third-button>{{ __('Confirm') }}</x-third-button>
                    </div>
                </form>
            </section>
        </div>
    </div>
</div>
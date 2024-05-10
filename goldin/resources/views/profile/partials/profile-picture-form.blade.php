<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Update profile picture') }}
        </h2>
    </header>

    <div class="mt-4">
        <img src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('images/default-avatar.png') }}" alt="Profile Picture">
    </div>

    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mt-4">
            <input type="file" name="avatar" accept="image/*">
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
        </div>
    </form>
</section>
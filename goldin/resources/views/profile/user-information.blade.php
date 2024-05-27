@extends('layouts.profile-layout')

@section('content')

    <!-- Quitar los DIVs si el usuari se ha logueado con Oauth-->

    <div class="py-12 bg-gray-100 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-5xl">
                    @include('profile.partials.information-user-form')
                </div>
                
                <button type="button" class='inline-flex items-center px-6 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-sm text-black dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150' onclick="window.location='{{ url('./edit-profile') }}'">
                    Edit profile
                </button>
            </div>
            <div class="p-4 sm:p-8 dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-5xl">
                    @include('profile.partials.inventory-user-form')
                </div>
            </div>
        </div>
    </div>
@endsection 
@extends('layouts.admin-layout')

@section('content')

    <div class="py-12 bg-gray-100 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Sección para modificar/crear/eliminar usuarios -->
            <div class="p-4 sm:p-8 dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-5xl" aria-label="User Management Section">
                    @include('admin.partials.users-form')
                </div>
            </div>

            <!-- Sección para modificar/crear/eliminar cajas -->
            <div class="p-4 sm:p-8 dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-5xl" aria-label="Clothes Management Section">
                    @include('admin.partials.clothes-form')
                </div>
            </div>

            <!-- Sección para modificar/crear/deshabilitar valores de los minijuegos -->
            <div class="p-4 sm:p-8 dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-5xl" aria-label="Minigames Management Section">
                    @include('admin.partials.minigames-form')
                </div>
            </div>
        </div>
    </div>

@endsection

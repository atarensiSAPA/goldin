@extends('layouts.admin-layout')

@section('content')

    <div class="py-12 bg-gray-100 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Sección para agregar usuarios -->
            <div class="p-4 sm:p-8 dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-5xl">
                    @include('admin.partials.admin-clothes-add')
                </div>
            </div>

            <!-- Sección para mostrar usuarios con sus botones -->
            <div class="p-4 sm:p-8 dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-5xl">
                    @include('admin.partials.admin-clothes-all')
                </div>
            </div>
        </div>
    </div>

@endsection

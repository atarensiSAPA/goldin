@extends('layouts.buyCart-layout')

@section('content')
<div class="py-12 bg-gray-100 dark:bg-gray-900 text-white">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="p-4 sm:p-8 dark:bg-gray-800 shadow sm:rounded-lg">
            <div class="max-w-5xl">
                @include('cart.partials.cartClothes')
            </div>
        </div>
        <div class="p-4 sm:p-8 dark:bg-gray-800 shadow sm:rounded-lg">
            <div class="max-w-5xl">
                @include('cart.partials.buyPage')
            </div>
        </div>
    </div>
</div>
@endsection

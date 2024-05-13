@extends('layouts.case')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="d-flex justify-content-center" style="margin-top: 20px;">
                    <div class="d-flex justify-content-center align-items-center" style="width: 600px; height: 300px; background: linear-gradient(rgba(0,0,0,0.8), rgba(0,0,0,0.8)); border-radius: 15px; border: 2px solid white;">
                        <img src="{{ asset('images/random-weapon.png') }}" alt="coin" width="175" height="175">
                    </div>
                </div>
                <div class="d-flex justify-content-center mt-3">
                    <button type="button" class='inline-flex items-center px-6 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-sm text-black dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150'>
                        Open Create For {{ $creates->firstWhere('cost', '!=', null)->cost }}<img src="{{ asset('images/user-coin.png') }}" alt="coin" width="30" height="30">
                    </button>
                </div>
                <div class="d-flex justify-content-center mt-3 flex-wrap">
                    @foreach ($creates as $create)
                        @if ($create->weapon)
                            <div class="mx-2 mb-3 dark:bg-gray-800" style="border: 3px solid {{ $create->color }}; border-radius: 15px; padding: 10px 10px 5px 10px; color:white;">
                                <img src="{{ asset('images/' . $create->weapon->weapon_img) }}" alt="{{ $create->weapon->name }}" width="235" height="235">
                                <p>€{{ $create->weapon->price }}</p>
                                <p>Chance: {{ $create->weapon->appearance_percentage }}%</p>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>


@endsection
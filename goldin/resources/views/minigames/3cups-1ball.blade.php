@extends('layouts.3cups-1ball-layout')

@section('content')

<div class="container">
    <div class="row mt-3">
        <div id="alertCoins" class="alert alert-danger alert-dismissible fade hideCard" role="alert">
            <span id="alert-messageCoins"></span>
        </div>
        <div class="col-md-12 text-center">
            <input type="number" id="bet-input" min="1" placeholder="Enter bet amount" class="w-15 m-2 border-0 rounded bg-light text-dark">
            <button id="bet" class='inline-flex items-center px-6 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-sm text-black dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150''>Bet</button>
            <p id="bet-display" class="text-white"></p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 text-center">
            <h1 class="mt-4 text-white">Find the Ball</h1>
            <div id="cups-container">
                <div class="cup" id="cup1"></div>
                <div class="cup" id="cup2"></div>
                <div class="cup" id="cup3"></div>
            </div>
        </div>
        <div class="text-center w-100">
            <div id="message" class="mt-3 text-white h4"></div>
            <div id="winnings" class="h5"></div>
        </div>
    </div>
</div>

@endsection
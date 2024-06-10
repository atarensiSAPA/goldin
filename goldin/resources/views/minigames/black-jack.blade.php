@extends('layouts.black-jack-layout')

@section('content')

<div class="container">
    <div class="row mt-3">
        <div id="alertCoins" class="alert alert-danger alert-dismissible fade hideCard" role="alert">
            <span id="alert-messageCoins"></span>
        </div>
        <div class="col-md-12 text-center">
            <label for="bet-input" class="visually-hidden">Enter bet amount</label>
            <input type="number" id="bet-input" min="1" placeholder="Enter bet amount" class="w-15 m-2 border-0 rounded bg-light text-dark">
            <button id="bet" class='inline-flex items-center px-6 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-sm text-black dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150'>Bet</button>
            <p id="bet-display" class="text-white"></p>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-6 float-left">
            <h2 class="text-white">Player's Hand</h2>
            <div id="player-hand" class="text-white text-2xl">
                <!-- Player's cards will go here -->
            </div>
            <button id="hit" class='inline-flex items-center px-6 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-sm text-black dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150'' disabled>Hit</button>
            <button id="stand" class='inline-flex items-center px-6 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-sm text-black dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150'' disabled>Stand</button>
        </div>
        <div class="col-md-6 float-right">
            <h2 class="text-white">Dealer's Hand</h2>
            <div id="dealer-hand" class="text-white text-2xl">
                <!-- Dealer's cards will go here -->
            </div>
        </div>
        <div class="text-center w-100">
            <div id="message" class="mt-3 text-white h4"></div>
            <div id="winnings" class="coin-animation text-success h5"></div>
        </div>
    </div>
</div>
@endsection

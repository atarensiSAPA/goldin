@extends('layouts.black-jack-layout')

@section('content')

<div class="container">
    <div class="row" style="margin-top: 20px;">
        <div class="col-md-12" style="text-align: center;">
            <input type="number" id="bet" min="1" placeholder="Enter bet amount" style="width: 15%; margin: 8px 0; box-sizing: border-box; border: none; border-radius: 4px; background-color: #f1f1f1; font-size: 16px;">
            <button id="start" class='inline-flex items-center px-6 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-sm text-black dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150'>Start</button>
        </div>
    </div>
    <div class="row" style="margin-top: 20px;">
        <div class="col-md-6" style="float: left;">
            <h2 style="color: white;">Player's Hand</h2>
            <div id="player-hand">
                <!-- Player's cards will go here -->
            </div>
            <button id="hit" class='inline-flex items-center px-6 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-sm text-black dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150'>Hit</button>
            <button id="stand" class='inline-flex items-center px-6 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-sm text-black dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150'>Stand</button>
        </div>
        <div class="col-md-6" style="float: right;">
            <h2 style="color: white;">Dealer's Hand</h2>
            <div id="dealer-hand">
                <!-- Dealer's cards will go here -->
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <br><button id="deal" class='inline-flex items-center px-6 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-sm text-black dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150'>Deal</button>
        </div>
    </div>
</div>

@endsection
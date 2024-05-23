@extends('layouts.black-jack-layout')

@section('content')

<div class="container">
    <div class="row" style="margin-top: 20px;">
        <div class="col-md-12" style="text-align: center;">
            <input type="number" id="bet-input" min="1" placeholder="Enter bet amount" style="width: 15%; margin: 8px 0; box-sizing: border-box; border: none; border-radius: 4px; background-color: #f1f1f1; font-size: 16px;">
            <button id="bet" class='inline-flex items-center px-6 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-sm text-black dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150'>Bet</button>
            <p id="bet-display" style="color: white"></p>
        </div>
    </div>
    <div class="row" style="margin-top: 20px;">
        <div class="col-md-6" style="float: left;">
            <h2 style="color: white;">Player's Hand</h2>
            <div id="player-hand" style="color: white;">
                <!-- Player's cards will go here -->
            </div>
            <button id="hit" class='inline-flex items-center px-6 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-sm text-black dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150'>Hit</button>
            <button id="stand" class='inline-flex items-center px-6 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-sm text-black dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150'>Stand</button>
        </div>
        <div class="col-md-6" style="float: right;">
            <h2 style="color: white;">Dealer's Hand</h2>
            <div id="dealer-hand" style="color: white;">
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

<script>
    let betAmount = 0;

    document.getElementById('bet').addEventListener('click', function() {
        betAmount = document.getElementById('bet-input').value;
        if(parseInt(betAmount) >= 100){
                betAmount = document.getElementById('bet-input').value;
                document.getElementById('bet-display').innerText = "User's bet: " + betAmount;

                //Enviar la petición AJAX
                $.ajax({
                    url: '/bet',
                    method: 'POST',
                    data: { bet: betAmount },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        //mostrar las monedas acutales del usuario
                        document.getElementById('coins').innerText = data.coins;
                        //iniciar el juego cuando el servidor devuelva la respuesta
                        blackJackMinigame();
                    },
                    error: function(error) {
                        console.error('Error:', error);
                    }
                });
        }else{
            alert("The bet needs to be at least 100 or higher");
        }
    });

    function blackJackMinigame(){
        if (betAmount > 0) {
            var deck = [];
            var suits = ["♠️", "♦️", "♣️", "♥️"];
            var values = ["A", "2", "3", "4", "5", "6", "7", "8", "9", "10", "J", "Q", "K"];

            // Crear la baraja de cartas
            for (var i = 0; i < suits.length; i++) {
                for (var x = 0; x < values.length; x++) {
                    var card = {Value: values[x], Suit: suits[i]};
                    deck.push(card);
                }
            }

            // Barajar la baraja
            deck.sort(function() { return 0.5 - Math.random() });

            var playerHand = [];
            var dealerHand = [];

            // Repartir las cartas iniciales
            playerHand.push(deck.pop());
            dealerHand.push(deck.pop());
            playerHand.push(deck.pop());
            dealerHand.push(deck.pop());

            // Mostrar las cartas iniciales
            displayHand('player-hand', playerHand);
            displayDealerHand(dealerHand, true);

            // Función para "golpear" y recibir una carta adicional
            document.getElementById('hit').addEventListener('click', function() {
                playerHand.push(deck.pop());
                displayHand('player-hand', playerHand);
            });

            // Función para "plantarse" y finalizar el turno del jugador
            document.getElementById('stand').addEventListener('click', function() {
                // El crupier recibe cartas hasta que su mano tenga un valor de 17 o más
                var dealerHandValue = calculateHandValue(dealerHand);
                var playerHandValue = calculateHandValue(playerHand);
                while (dealerHandValue < 17 || (dealerHandValue < playerHandValue && dealerHandValue <= 21)) {
                    dealerHand.push(deck.pop());
                    dealerHandValue = calculateHandValue(dealerHand);
                }
                displayDealerHand(dealerHand, false);
            });
            function displayHand(handElementId, hand) {
                var handHtml = "";
                for (var i = 0; i < hand.length; i++) {
                    handHtml += hand[i].Value + hand[i].Suit + " ";
                }
                document.getElementById(handElementId).innerHTML = handHtml;
            }

            function displayDealerHand(hand, hideCard) {
                var handHtml = "";
                for (var i = 0; i < hand.length; i++) {
                    if (i == 0 && hideCard) {
                        handHtml += "? ";
                    } else {
                        handHtml += hand[i].Value + hand[i].Suit + " ";
                    }
                }
                document.getElementById('dealer-hand').innerHTML = handHtml;
            }

            displayHand('player-hand', playerHand);
            displayHand('dealer-hand', dealerHand);

            // Update the "hit" and "stand" event listeners to use the new displayHand function
            document.getElementById('hit').addEventListener('click', function() {
                playerHand.push(deck.pop());
                displayHand('player-hand', playerHand);
            });

            document.getElementById('stand').addEventListener('click', function() {
                // ... rest of the code ...
                displayHand('dealer-hand', dealerHand);
            });

            // Función para calcular el valor de una mano
            function calculateHandValue(hand) {
                var value = 0;
                for (var i = 0; i < hand.length; i++) {
                    var cardValue = hand[i].Value;
                    if (cardValue == "J" || cardValue == "Q" || cardValue == "K") {
                        value += 10;
                    } else if (cardValue == "A") {
                        value += 11;
                    } else {
                        value += parseInt(cardValue);
                    }
                }
                return value;
            }
        } else {
            alert("Please place a bet before starting the game.");
        }
    }

    
</script>

@endsection
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
        <div style="text-align: center;">
            <div id="message" class="mt-3" style="color: white; font-size: 30px"></div>
            <div id="winnings" class="coin-animation" style="color: green; font-size: 25px"></div>
        </div>
    </div>
</div>

<script>
    let betAmount = 0;
    let betButton = document.getElementById('bet');

    betButton.addEventListener('click', function() {
        let betInput = document.getElementById('bet-input').value;
        betAmount = parseInt(betInput);
        if(betInput == betAmount && betAmount >= 100){
            //Enviar la petición AJAX
            $.ajax({
                url: '/bet',
                method: 'POST',
                data: { bet: betAmount },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    document.getElementById('bet-display').innerText = "User's bet: " + betAmount;
                    //mostrar las monedas actuales del usuario
                    document.getElementById('coins').innerText = data.coins;

                    // Habilitar los botones de "hit" y "stand"
                    document.getElementById('hit').disabled = false;
                    document.getElementById('stand').disabled = false;
                    document.getElementById('winnings').innerHTML = "";
                    document.getElementById('message').innerHTML = "Click hit or stand to play the game";
                    //deshabilitar boton de bet una vez apostado
                    betButton.disabled = true;
                    
                    //iniciar el juego cuando el servidor devuelva la respuesta
                    blackJackMinigame();
                },
                error: function(error) {
                    console.error('Error:', error);
                    alert(error.responseJSON.message);
                }
            });
        } else {
            alert("The bet needs to be an integer and at least 100 or higher");
        }
    });

    function blackJackMinigame(){
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

        // Mostrar las cartas iniciales
        displayHand('player-hand', playerHand);
        displayDealerHand(dealerHand, true);

        // Limpiar eventos previos para evitar múltiples registros
        $('#hit').off('click').on('click', function() {
            playerHand.push(deck.pop());
            displayHand('player-hand', playerHand);

            var playerValue = calculateHandValue(playerHand);

            // Si el jugador se pasa de 21, pierde automáticamente
            if (playerValue > 21) {
                endGame("Player busts. Dealer wins.", false);
            }

            // Si el jugador tiene 21, se revela la mano del dealer y el dealer juega su turno
            if (playerValue === 21) {
                revealDealerHand();
                var dealerValue = calculateHandValue(dealerHand);

                // El crupier recibe cartas hasta que su mano tenga un valor mayor que el del jugador o hasta que se pase de 21
                while (dealerValue <= playerValue && dealerValue <= 21) {
                    dealerHand.push(deck.pop());
                    dealerValue = calculateHandValue(dealerHand);
                }

                displayDealerHand(dealerHand, false);
                determineWinner(playerValue, dealerValue);
            }
        });

        $('#stand').off('click').on('click', function() {
            // Deshabilitar los botones "Hit" y "Stand"
            document.getElementById('hit').disabled = true;
            document.getElementById('stand').disabled = true;

            // Mostrar todas las cartas del dealer
            revealDealerHand();

            // Calcular los valores de las manos
            var playerValue = calculateHandValue(playerHand);
            var dealerValue = calculateHandValue(dealerHand);

            // El crupier recibe cartas hasta que su mano tenga un valor mayor que el del jugador o hasta que se pase de 21
            while (dealerValue <= playerValue && dealerValue <= 21) {
                dealerHand.push(deck.pop());
                dealerValue = calculateHandValue(dealerHand);
            }

            displayDealerHand(dealerHand, false);
            determineWinner(playerValue, dealerValue);
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
                if (i == 0 || !hideCard) {
                    handHtml += hand[i].Value + hand[i].Suit + " ";
                } else {
                    handHtml += "? ";
                }
            }
            document.getElementById('dealer-hand').innerHTML = handHtml;
        }

        function revealDealerHand() {
            displayDealerHand(dealerHand, false);
        }

        // Función para calcular el valor de una mano
        function calculateHandValue(hand) {
            var value = 0;
            var aces = 0;

            for (var i = 0; i < hand.length; i++) {
                var cardValue = hand[i].Value;
                if (cardValue == "J" || cardValue == "Q" || cardValue == "K") {
                    value += 10;
                } else if (cardValue == "A") {
                    aces += 1;
                    value += 11;
                } else {
                    value += parseInt(cardValue);
                }
            }

            // Si el valor total es mayor que 21 y tenemos un As, tratamos el As como 1 en lugar de 11
            while (value > 21 && aces > 0) {
                value -= 10;
                aces -= 1;
            }

            return value;
        }

        function determineWinner(playerValue, dealerValue) {
            var message = "";
            var playerWins = false;
            var tie = false;
            if (playerValue === 21 && playerHand.length === 2 && dealerValue === 21 && dealerHand.length === 2) {
                message = "Both player and dealer have Black Jack. It's a tie.";
                tie = true;
            } else if (playerValue === 21 && playerHand.length === 2) {
                message = "Player has Black Jack. Player wins.";
                playerWins = true;
            } else if (dealerValue === 21 && dealerHand.length === 2) {
                message = "Dealer has Black Jack. Dealer wins.";
                playerWins = false;
            } else if (playerValue > 21) {
                message = "Player busts. Dealer wins.";
                playerWins = false;
            } else if (dealerValue > 21) {
                message = "Dealer busts. Player wins.";
                playerWins = true;
            } else if (playerValue > dealerValue) {
                message = "Player wins.";
                playerWins = true;
            } else if (dealerValue > playerValue && dealerValue <= 21) {
                message = "Dealer wins.";
                playerWins = false;
            } else {
                message = "It's a tie.";
                tie = true;
            }

            // Si el jugador gana, enviar una petición AJAX para actualizar las monedas del jugador
            if (playerWins || tie) {
                var winnersMoney = playerWins ? Math.round(betAmount * 1.25) - betAmount : betAmount;
                ajaxSendBet(winnersMoney, message, playerWins, tie);
            } else {
                endGame(message, false);
            }
        }

        function ajaxSendBet(winnersMoney, message, playerWins, tie) {
            $.ajax({
                url: '/update-coins',
                method: 'POST',
                data: { coins: winnersMoney },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    var coinElement = document.getElementById('coins');
                    coinElement.innerText = data.coins;

                    var winningsElement = document.getElementById('winnings');
                    if(tie){
                        winningsElement.innerText = "It's a tie. Your bet of " + betAmount + " coins has been returned!";
                    } else if(playerWins){
                        var total = parseInt(winnersMoney) + parseInt(betAmount);
                        winningsElement.innerText = "Player wins! You have won " + total + " coins!";
                    } else {
                        winningsElement.innerText = "You lost your bet!";
                    }

                    endGame(message, playerWins);
                },
                error: function(error) {
                    console.error('Error:', error);
                    alert(error.responseJSON.message);
                }
            });
        }

        function endGame(message, playerWins) {
            // Mostrar el mensaje de quién ha ganado
            document.getElementById('message').textContent = message;

            // Habilitar botón de apuesta
            betButton.disabled = false;

            // Deshabilitar los botones de "hit" y "stand"
            document.getElementById('hit').disabled = true;
            document.getElementById('stand').disabled = true;
        }
    }
</script>

@endsection
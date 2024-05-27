let betAmount = 0;
let betButton = document.getElementById('bet');

betButton.addEventListener('click', function() {
    let betInput = document.getElementById('bet-input').value;
    betAmount = parseInt(betInput);
    if (betInput == betAmount && betAmount >= 100) {
        // Enviar la petición AJAX
        $.ajax({
            url: '/bet',
            method: 'POST',
            data: { bet: betAmount },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) {
                document.getElementById('bet-display').innerText = "User's bet: " + betAmount;
                // Mostrar las monedas actuales del usuario
                document.getElementById('coins').innerText = data.coins;

                // Habilitar los botones de "hit" y "stand"
                document.getElementById('hit').disabled = false;
                document.getElementById('stand').disabled = false;
                document.getElementById('winnings').innerHTML = "";
                document.getElementById('message').innerHTML = "Click hit or stand to play the game";
                // Deshabilitar botón de bet una vez apostado
                betButton.disabled = true;

                // Iniciar el juego cuando el servidor devuelva la respuesta
                blackJackMinigame();
            },
            error: function(error) {
                console.error('Error:', error);

                let alertDiv = document.getElementById('alertCoins');
                alertDiv.classList.remove('hideCard'); // Mostrar el alert
                alertDiv.classList.add('show'); // Asegurarse de que el alert tenga la clase show
                document.getElementById('alert-messageCoins').textContent = error.responseJSON.message;

                setTimeout(function() {
                    alertDiv.classList.remove('show'); // Ocultar alert
                    alertDiv.classList.add('hideCard'); // Asegurarse de que el alert tenga la clase hide
                }, 3000); // Ocultar después de 3 segundos
            }
        });
    } else {
        // Asigna el mensaje de error al elemento #alert-messageCoins
        document.getElementById('alert-messageCoins').innerText = "The bet needs to be an integer and at least 100 or higher";

        // Muestra el alerta
        let alertDiv = document.getElementById('alertCoins');
        alertDiv.classList.remove('hideCard');
        alertDiv.classList.add('show');

        // Configura un temporizador para ocultar el alerta después de 3 segundos
        setTimeout(function() {
            alertDiv.classList.remove('show');
            alertDiv.classList.add('hideCard');
        }, 3000);
    }
});

    function blackJackMinigame(){
        let deck = [];
        let suits = ["♠️", "♦️", "♣️", "♥️"];
        let values = ["A", "2", "3", "4", "5", "6", "7", "8", "9", "10", "J", "Q", "K"];

        // Crear la baraja de cartas
        for (let i = 0; i < suits.length; i++) {
            for (let x = 0; x < values.length; x++) {
                let card = {Value: values[x], Suit: suits[i]};
                deck.push(card);
            }
        }

        // Barajar la baraja
        deck.sort(function() { return 0.5 - Math.random() });

        let playerHand = [];
        let dealerHand = [];

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

            let playerValue = calculateHandValue(playerHand);

            // Si el jugador se pasa de 21, pierde automáticamente
            if (playerValue > 21) {
                endGame("Player busts. Dealer wins.", false);
            }

            // Si el jugador tiene 21, se revela la mano del dealer y el dealer juega su turno
            if (playerValue === 21) {
                revealDealerHand();
                let dealerValue = calculateHandValue(dealerHand);

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
            let playerValue = calculateHandValue(playerHand);
            let dealerValue = calculateHandValue(dealerHand);

            // El crupier recibe cartas hasta que su mano tenga un valor mayor que el del jugador o hasta que se pase de 21
            while (dealerValue <= playerValue && dealerValue <= 21) {
                dealerHand.push(deck.pop());
                dealerValue = calculateHandValue(dealerHand);
            }

            displayDealerHand(dealerHand, false);
            determineWinner(playerValue, dealerValue);
        });

        function displayHand(handElementId, hand) {
            let handHtml = "";
            for (let i = 0; i < hand.length; i++) {
                handHtml += hand[i].Value + hand[i].Suit + " ";
            }
            document.getElementById(handElementId).innerHTML = handHtml;
        }

        function displayDealerHand(hand, hideCard) {
            let handHtml = "";
            for (let i = 0; i < hand.length; i++) {
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
            let value = 0;
            let aces = 0;

            for (let i = 0; i < hand.length; i++) {
                let cardValue = hand[i].Value;
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
            let message = "";
            let playerWins = false;
            let tie = false;
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
        
            // Si el jugador gana o es un empate, enviar una petición AJAX para actualizar las monedas del jugador
            if (playerWins || tie) {
                ajaxSendBet(betAmount, playerWins, message, tie);
            } else {
                endGame(message, false);
            }
        }
        
        function ajaxSendBet(betAmount, playerWins, message, tie) {
            $.ajax({
                url: '/update-coins',
                method: 'POST',
                data: { bet: betAmount, won: playerWins },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    let coinElement = document.getElementById('coins');
                    coinElement.innerText = Math.round(data.coins);
            
                    let winningsElement = document.getElementById('winnings');
                    if(tie){
                        winningsElement.innerText = "It's a tie. Your bet of " + betAmount + " coins has been returned!";
                    } else if(playerWins){
                        winningsElement.innerText = "Player wins! You have won " + Math.round(data.winnings) + " coins!";
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
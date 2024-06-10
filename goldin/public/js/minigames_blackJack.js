let betAmount = 0;
let betButton = document.getElementById('bet');

// Event listener for the bet button
betButton.addEventListener('click', function() {
    try {
        placeBet();
    } catch (error) {
        handleBetError(error);
    }
});

function placeBet() {
    let betInput = document.getElementById('bet-input').value;
    betAmount = parseInt(betInput);

    // Validate the bet input
    if (!isValidBetInput(betInput)) {
        displayBetInputError();
        return;
    }

    // Send AJAX request to place the bet
    $.ajax({
        url: '/bet',
        method: 'POST',
        data: { bet: betAmount },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(data) {
            handleBetSuccess(data);
        },
        error: function(error) {
            displayAlert('alertCoins', 'alert-messageCoins', error.responseJSON.message);
            throw new Error(error.responseJSON.message);
        }
    });
}

function isValidBetInput(betInput) {
    return betInput == betAmount && betAmount >= 100;
}

function displayBetInputError() {
    displayAlert('alertCoins', 'alert-messageCoins', 'The bet needs to be an integer and at least 100 or higher');
}

function handleBetSuccess(data) {
    document.getElementById('bet-display').innerText = "User's bet: " + betAmount;
    document.getElementById('coins').innerText = data.coins;
    document.getElementById('hit').disabled = false;
    document.getElementById('stand').disabled = false;
    document.getElementById('winnings').innerHTML = "";
    document.getElementById('message').innerHTML = "Click hit or stand to play the game";
    betButton.disabled = true;
    blackJackMinigame();
}

function handleBetError(error) {
    console.error('Error:', error);
    displayAlert('alertCoins', 'alert-messageCoins', error.message);
}

function displayAlert(alertId, messageId, message) {
    let alertDiv = document.getElementById(alertId);
    alertDiv.classList.remove('hideCard');
    alertDiv.classList.add('show');
    document.getElementById(messageId).textContent = message;

    setTimeout(function() {
        alertDiv.classList.remove('show');
        alertDiv.classList.add('hideCard');
    }, 3000);
}

function blackJackMinigame() {
    let deck = [];
    let suits = ["♠️", "♦️", "♣️", "♥️"];
    let values = ["A", "2", "3", "4", "5", "6", "7", "8", "9", "10", "J", "Q", "K"];

    // Create the deck of cards
    for (let i = 0; i < suits.length; i++) {
        for (let x = 0; x < values.length; x++) {
            let card = {Value: values[x], Suit: suits[i]};
            deck.push(card);
        }
    }

    // Shuffle the deck
    deck.sort(function() { return 0.5 - Math.random() });

    let playerHand = [];
    let dealerHand = [];

    // Deal the initial cards
    playerHand.push(deck.pop());
    dealerHand.push(deck.pop());
    playerHand.push(deck.pop());

    // Calculate the values of the hands
    let playerValue = calculateHandValue(playerHand);
    let dealerValue = calculateHandValue(dealerHand);

    // If the player has Black Jack, reveal the dealer's hand and determine the winner
    if (playerValue === 21) {
        displayHand('player-hand', playerHand);
        displayDealerHand(dealerHand, true);
        dealerHand.push(deck.pop());
        dealerValue = calculateHandValue(dealerHand);
        revealDealerHand();
        determineWinner(playerValue, dealerValue);
    } else {
        // Show the initial cards
        displayHand('player-hand', playerHand);
        displayDealerHand(dealerHand, true);
    }

    // Clear previous events to avoid multiple registrations
    $('#hit').off('click').on('click', function() {
        playerHand.push(deck.pop());
        displayHand('player-hand', playerHand);
        let playerValue = calculateHandValue(playerHand);

        // If the player busts, the dealer wins
        if (playerValue > 21) {
            endGame("Player busts. Dealer wins.", false);
        }

        // If the player hits 21, reveal the dealer's hand and play the dealer's turn
        if (playerValue === 21) {
            revealDealerHand();
            let dealerValue = calculateHandValue(dealerHand);

            // Dealer plays until they beat the player or bust
            while (dealerValue <= playerValue && dealerValue <= 21) {
                dealerHand.push(deck.pop());
                dealerValue = calculateHandValue(dealerHand);
            }

            displayDealerHand(dealerHand, false);
            determineWinner(playerValue, dealerValue);
        }
    });

    $('#stand').off('click').on('click', function() {
        // Disable the "hit" and "stand" buttons
        document.getElementById('hit').disabled = true;
        document.getElementById('stand').disabled = true;

        // Reveal all of the dealer's cards
        revealDealerHand();
        let playerValue = calculateHandValue(playerHand);
        let dealerValue = calculateHandValue(dealerHand);

        // Dealer plays until they beat the player or bust
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

        // Adjust value for aces if necessary
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
        let blackJack = false;

        // Determine the winner based on the values of the hands
        if (playerValue === 21 && playerHand.length === 2 && dealerValue === 21 && dealerHand.length === 2) {
            message = "Both player and dealer have Black Jack. It's a tie.";
            tie = true;
        } else if (playerValue === 21 && playerHand.length === 2) {
            message = "Player has Black Jack. Player wins.";
            playerWins = true;
            blackJack = true;
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

        // If the player wins or it's a tie, send an AJAX request to update the player's coins
        if (playerWins || tie) {
            ajaxSendBet(betAmount, playerWins, message, tie, blackJack);
        } else {
            endGame(message, false);
        }
    }

    function ajaxSendBet(betAmount, playerWins, message, tie, blackJack) {
        $.ajax({
            url: '/update-coins',
            method: 'POST',
            data: { bet: betAmount, won: playerWins, blackJack: blackJack },
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
        // Display the message of who won
        document.getElementById('message').textContent = message;

        // Enable the bet button
        betButton.disabled = false;

        // Disable the "hit" and "stand" buttons
        document.getElementById('hit').disabled = true;
        document.getElementById('stand').disabled = true;
    }
}

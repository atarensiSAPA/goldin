let betAmount = 0;
let betButton = document.getElementById('bet');

// Event listener for the bet button
betButton.addEventListener('click', function() {
    try {
        let betInput = document.getElementById('bet-input').value;
        betAmount = parseInt(betInput);

        // Validate the bet input
        if (betInput != betAmount || betAmount < 100) {
            throw new Error("The bet needs to be an integer and at least 100 or higher");
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
                document.getElementById('bet-display').innerText = "User's bet: " + betAmount;
                document.getElementById('coins').innerText = data.coins;

                document.getElementById('message').innerHTML = "";
                document.getElementById('winnings').innerHTML = "";

                betButton.disabled = true;

                resetGame();
                cupsMinigame();
            },
            error: function(error) {
                console.error('Error:', error);
                showAlert('alertCoins', 'alert-messageCoins', error.responseJSON.message);
            }
        });
    } catch (error) {
        console.error('Error:', error);
        showAlert('alertCoins', 'alert-messageCoins', error.message);
    }
});

// Function to reset the game
function resetGame() {
    const cups = document.querySelectorAll('.cup');
    cups.forEach(cup => {
        if (cup.firstChild) {
            cup.firstChild.remove();
        }
        cup.style.pointerEvents = 'auto';
    });
}

const cups = document.querySelectorAll('.cup');
let ballPosition;
const messageDiv = document.getElementById('message');

cups.forEach((cup, index) => {
    cup.addEventListener('click', () => {
        try {
            cups[ballPosition].appendChild(document.createElement('div')).className = 'ball';

            let userWon = (index === ballPosition);
            messageDiv.textContent = userWon ? 'Correct!' : 'Incorrect!';

            cups.forEach(cup => {
                cup.style.pointerEvents = 'none';
            });

            // Send the AJAX request to update the user's coins
            $.ajax({
                url: '/update-coins',
                method: 'POST',
                data: {
                    bet: betAmount,
                    won: userWon,
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    let coinElement = document.getElementById('coins');
                    coinElement.innerText = Math.round(data.coins);

                    let winningsElement = document.getElementById('winnings');
                    let winnersMoney = Math.round(data.winnings);
                    winningsElement.innerText = userWon ? "You have won " + Math.round(winnersMoney) + " coins!" : "You lost your bet!";
                    winningsElement.style.color = userWon ? 'green' : 'red';

                    betButton.disabled = false;
                },
                error: function(error) {
                    console.error('Error:', error);
                    showAlert('alertCoins', 'alert-messageCoins', error.responseJSON.message);
                }
            });
        } catch (error) {
            console.error('Error:', error);
            showAlert('alertCoins', 'alert-messageCoins', error.message);
        }
    });
});

// Function to start the cups minigame
function cupsMinigame() {
    ballPosition = Math.floor(Math.random() * 3);
    messageDiv.textContent = 'Choose a cup!';
}

// Function to display alert messages
function showAlert(alertId, messageId, message) {
    const alertDiv = document.getElementById(alertId);
    alertDiv.classList.remove('hideCard');
    alertDiv.classList.add('show');
    document.getElementById(messageId).textContent = message;

    setTimeout(() => {
        $(alertDiv).fadeOut(1000, function() {
            $(this).css('display', 'none');
        });
    }, 3000);
}

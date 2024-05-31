let betAmount = 0;
let betButton = document.getElementById('bet');

// Event listener for the bet button
betButton.addEventListener('click', function() {
    let betInput = document.getElementById('bet-input').value;
    betAmount = parseInt(betInput);

    // Validate the bet input
    if (betInput == betAmount && betAmount >= 100) {
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
                // Display the user's current coins
                document.getElementById('coins').innerText = data.coins;

                document.getElementById('message').innerHTML = "";
                document.getElementById('winnings').innerHTML = "";

                // Disable the bet button once a bet is placed
                betButton.disabled = true;

                // Reset the game
                resetGame();

                // Start the game when the server returns the response
                cupsMinigame();
            },
            error: function(error) {
                console.error('Error:', error);

                // Show the alert
                let alertDiv = document.getElementById('alertCoins');
                alertDiv.classList.remove('hideCard'); // Show the alert
                alertDiv.classList.add('show'); // Ensure the alert has the 'show' class
                document.getElementById('alert-messageCoins').textContent = error.responseJSON.message;

                // Hide the alert after 3 seconds
                setTimeout(function() {
                    $(alertDiv).fadeOut(1000, function() {
                        $(this).css('display', 'none');
                    });
                }, 3000);
            }
        });
    } else {
        // Set the error message to the #alert-messageCoins element
        document.getElementById('alert-messageCoins').innerText = "The bet needs to be an integer and at least 100 or higher";

        // Show the alert
        let alertDiv = document.getElementById('alertCoins');
        alertDiv.classList.remove('hideCard');
        alertDiv.classList.add('show');

        // Set a timer to hide the alert after 3 seconds
        setTimeout(function() {
            alertDiv.classList.remove('show');
            alertDiv.classList.add('hideCard');
        }, 3000);
    }
});

// Function to reset the game
function resetGame() {
    const cups = document.querySelectorAll('.cup');
    // Remove the ball from the previous view
    cups.forEach(cup => {
        if (cup.firstChild) {
            cup.firstChild.remove();
        }
        // Make the cups clickable again
        cup.style.pointerEvents = 'auto';
    });
}

const cups = document.querySelectorAll('.cup');
let ballPosition;
const messageDiv = document.getElementById('message');

cups.forEach((cup, index) => {
    cup.addEventListener('click', () => {
        // Generate the ball when a cup is clicked
        cups[ballPosition].appendChild(document.createElement('div')).className = 'ball';

        let userWon = false;
        if (index === ballPosition) {
            messageDiv.textContent = 'Correct!';
            userWon = true;
        } else {
            messageDiv.textContent = 'Incorrect!';
        }

        // Disable the cups after a choice is made
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

                // Re-enable the bet button
                betButton.disabled = false;
            },
            error: function(error) {
                console.error('Error:', error);
                alert(error.responseJSON.message);
            }
        });
    });
});

// Function to start the cups minigame
function cupsMinigame() {
    ballPosition = Math.floor(Math.random() * 3); // Ball position is generated at the start of the game

    // Display a message for the user to choose a cup
    messageDiv.textContent = 'Choose a cup!';
}

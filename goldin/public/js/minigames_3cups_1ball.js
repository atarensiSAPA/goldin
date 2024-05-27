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

                document.getElementById('message').innerHTML = "";
                document.getElementById('winnings').innerHTML = "";

                betButton.disabled = true;

                // Reiniciar el juego
                resetGame();

                // Iniciar el juego cuando el servidor devuelva la respuesta
                cupsMinigame();

            },
            error: function(error) {
                console.error('Error:', error);
            
                let alertDiv = document.getElementById('alertCoins');
                alertDiv.classList.remove('hideCard'); // Mostrar el alert
                alertDiv.classList.add('show'); // Asegurarse de que el alert tenga la clase show
                document.getElementById('alert-messageCoins').textContent = error.responseJSON.message;
            
                setTimeout(function() {
                    $(alertDiv).fadeOut(1000, function() {
                        $(this).css('display', 'none');
                    });
                }, 3000);
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

function resetGame() {
    const cups = document.querySelectorAll('.cup');
    // Eliminar la bola de la vista anterior
    cups.forEach(cup => {
        if (cup.firstChild) {
            cup.firstChild.remove();
        }
        // Permitir que los vasos sean clicables de nuevo
        cup.style.pointerEvents = 'auto';
    });
}

const cups = document.querySelectorAll('.cup');
let ballPosition;
const messageDiv = document.getElementById('message');

cups.forEach((cup, index) => {
    cup.addEventListener('click', () => {
        // Generar la bola cuando se hace clic en un vaso
        cups[ballPosition].appendChild(document.createElement('div')).className = 'ball';

        let userWon = false;
        if (index === ballPosition) {
            messageDiv.textContent = 'Correct!';
            userWon = true;
        } else {
            messageDiv.textContent = 'Incorrect!';
        }

        // Deshabilitar los vasos después de hacer una elección
        cups.forEach(cup => {
            cup.style.pointerEvents = 'none';
        });

        // Enviar la petición AJAX para actualizar las monedas del usuario
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
                alert(error.responseJSON.message);
            }
        });
    });
});

function cupsMinigame() {
    ballPosition = Math.floor(Math.random() * 3); // Posición de la bola se genera al inicio del juego

    // Mostrar mensaje para que el usuario elija un vaso
    messageDiv.textContent = 'Choose a cup!';
}
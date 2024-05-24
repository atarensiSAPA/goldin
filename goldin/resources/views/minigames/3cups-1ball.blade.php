@extends('layouts.3cups-1ball-layout')

@section('content')

<div class="container">
    <div class="row" style="margin-top: 20px;">
        <div class="col-md-12" style="text-align: center;">
            <input type="number" id="bet-input" min="1" placeholder="Enter bet amount" style="width: 15%; margin: 8px 0; box-sizing: border-box; border: none; border-radius: 4px; background-color: #f1f1f1; font-size: 16px;">
            <button id="bet" class='inline-flex items-center px-6 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-sm text-black dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150'>Bet</button>
            <p id="bet-display" style="color: white"></p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12" style="text-align: center;">
            <h1 class="mt-4" style="color: white;">Find the Ball</h1>
            <div id="cups-container">
                <div class="cup" id="cup1"></div>
                <div class="cup" id="cup2"></div>
                <div class="cup" id="cup3"></div>
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

    document.getElementById('bet').addEventListener('click', function() {
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
                    //mostrar las monedas acutales del usuario
                    document.getElementById('coins').innerText = data.coins;

                    document.getElementById('message').innerHTML = "";
                    document.getElementById('winnings').innerHTML = "";
                    
                    //reiniciar el juego
                    resetGame();

                    //iniciar el juego cuando el servidor devuelva la respuesta
                    cupsMinigame();
                    
                },
                error: function(error) {
                    console.error('Error:', error);
                    alert(error.responseJSON.message);
                }
            });
    }else{
        alert("The bet needs to be an integer and at least 100 or higher");
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

            let winnersMoney;
            if (index === ballPosition) {
                messageDiv.textContent = 'Correct!';
                winnersMoney = parseInt(betAmount) * 1.25;
            } else {
                messageDiv.textContent = 'Incorrect!';
                winnersMoney = 0;
            }

            // Deshabilitar los vasos después de hacer una elección
            cups.forEach(cup => {
                cup.style.pointerEvents = 'none';
            });

            // Enviar la petición AJAX para actualizar las monedas del usuario
            $.ajax({
                url: '/update-coins',
                method: 'POST',
                data: { coins: winnersMoney },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    document.getElementById('coins').innerText = data.coins;
                    document.getElementById('winnings').innerText = winnersMoney > 0 ? "You have won " + winnersMoney + " coins!" : "You lost your bet!";
                },
                error: function(error) {
                    console.error('Error:', error);
                    alert(error.responseJSON.message);
                }
            });
        });
    });
    function cupsMinigame(){
        ballPosition = Math.floor(Math.random() * 3); // Posición de la bola se genera al inicio del juego

        // Mostrar mensaje para que el usuario elija un vaso
        messageDiv.textContent = 'Choose a cup!';
    }
</script>

@endsection
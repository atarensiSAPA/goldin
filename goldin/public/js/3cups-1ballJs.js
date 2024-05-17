const cups = document.querySelectorAll('.cup');
const startButton = document.getElementById('start');
const guessButton = document.getElementById('guess');
const guessInput = document.getElementById('guess-input');
const resultDiv = document.getElementById('result');

let ballPosition = 0;

guessButton.addEventListener('click', () => {
  const guess = parseInt(guessInput.value);
  if (guess >= 1 && guess <= 3) {
    if (guess === ballPosition + 1) {
      resultDiv.textContent = 'Correct!';
      startButton.textContent = 'Start Again'; // Change the start button text if the user won
    } else {
      resultDiv.textContent = 'Incorrect!';
      startButton.textContent = 'Try Again'; // Change the start button text if the user lost
    }
    cups[ballPosition].appendChild(document.createElement('div')).className = 'ball'; // Show the ball after the guess
    guessButton.disabled = true; // Disable the guess button after a valid guess is made
  } else {
    resultDiv.textContent = 'Invalid guess!';
  }
});

startButton.addEventListener('click', () => {
  ballPosition = Math.floor(Math.random() * 3);
  cups.forEach((cup, index) => {
    cup.querySelector('.ball')?.remove();
  });
  resultDiv.textContent = '';
  guessButton.disabled = false; // Enable the guess button when the game starts
  startButton.textContent = 'Start'; // Reset the start button text when the game starts
});
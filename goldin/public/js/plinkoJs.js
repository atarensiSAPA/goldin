const canvas = document.getElementById('plinkoCanvas');
const ctx = canvas.getContext('2d');

const pegs = [];
const radius = 10;
const gap = 50;
const rows = 10;
const cols = 10;

// Box the pegs
for (let i = 0; i < rows; i++) {
    for (let j = 0; j < cols; j++) {
        const x = gap / 2 + j * gap;
        const y = gap / 2 + i * gap;
        pegs.push({ x, y });
    }
}

// Draw the pegs
function drawPegs() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    ctx.fillStyle = 'white';
    for (const peg of pegs) {
        ctx.beginPath();
        ctx.arc(peg.x, peg.y, radius, 0, Math.PI * 2);
        ctx.fill();
    }
}

drawPegs();

// Add event listener to the drop button
document.getElementById('dropButton').addEventListener('click', () => {
    // TODO: Add code to drop the ball
});
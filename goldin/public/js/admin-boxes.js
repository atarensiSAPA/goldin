document.getElementById('isDaily').addEventListener('change', function() {
    var weaponsSection = document.getElementById('weaponsSection');
    if (this.checked) {
        weaponsSection.style.display = 'none';
    } else {
        weaponsSection.style.display = 'block';
    }
});
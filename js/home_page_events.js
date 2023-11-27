document.addEventListener('DOMContentLoaded', () => {
    const p1 = document.getElementById('p1');
    const start_button = document.getElementById('start_b');

    start_button.addEventListener('click', () => {
        window.location.href = 'login.html';
    })
});
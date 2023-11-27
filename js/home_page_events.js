document.addEventListener('DOMContentLoaded', () => {
    const make_account_b = document.getElementById('make_account_b');
    const login_b = document.getElementById('login_b')

    make_account_b.addEventListener('click', () => {
        window.location.href = 'make_account_page.html';
    });

    login_b.addEventListener('click', () => {
        window.location.href = 'login_page.html';
    });
});
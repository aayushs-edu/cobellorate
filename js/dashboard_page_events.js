document.addEventListener('DOMContentLoaded', () => {
    const create_project_button = document.getElementById('new-project-button');

    create_project_button.addEventListener('click', () => {
        window.location.href = 'project_creation_page.html';
    });
});
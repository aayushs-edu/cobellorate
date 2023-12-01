// script for updating the project links on the dashboard
document.addEventListener('DOMContentLoaded', () => {
    $(document).ready(function () {
        $.ajax({
            url: '../php/dashboard.php',
            method: 'GET',
            dataType: 'json',
            success: function (data) {
                if (data.length > 0) {
                    var projectList = $('#project-list');
                    data.forEach(function (project) {
                        projectList.append('<a href="./projects/' + projects[1] + '" class="project-link">' + project[0] + '</a>');
                    });
                } else {
                    $('#project-list').html('<p>No projects created yet</p>');
                }
            },
            error: function (xhr, status, error) {
                console.error('AJAX error: ' + status, error);
            }
        });
    });
})


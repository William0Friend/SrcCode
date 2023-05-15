$(document).ready(function() {
    fetch('get_questions.php')
        .then(response => response.json())
        .then(data => {
            let html = '';
            data.forEach(question => {
                html += `<h3>${question.title}</h3>`;
                html += `<p>${question.body}</p>`;
                html += question.answered ? '<p>Answered</p>' : '<p>Not answered</p>';
                html += '<hr>';
            });
            $('#questionContainer').html(html);
        });
});
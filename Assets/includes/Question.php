<?php
session_start();

if (!isset($_SESSION['loggedin'])) {
     header('Location: Login.php');
     exit;
 }
?>


<!-- question.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Question</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <!--Javascript-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.js" integrity="sha256-a9jBBRygX1Bh5lt8GZjXDzyOB+bWve9EiO7tROUtj/E=" crossorigin="anonymous"></script></head>
<body>

<header>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" title="SrcCode - Sell Your Src Code">$rcCode</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="Register.php" title="Register">Register</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="Register_ReCAPTCHA.php" title="Register">Register_ReCAPTCHA</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="Login.php" title="Login">Login</a>
                </li>
                <!-- pages only avaalile to the user -->
                <?php if (isset($_SESSION["loggedin"])): ?>
                <li class="nav-item">
                    <a class="nav-link active" href="Question.php" title="question">Question</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="User.php" title="User">User</a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link" href="Post_Question.php" title="Post Question">Post</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="Sell_Source_Code.php" title="Sell Source Code">Sell</a>
                </li>
                <?php endif; ?>
                <li class="nav-item">
                    <a class="nav-link" href="AboutUs.php" title="Register">About</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

    </header>


    <div id="question">
        <!-- The question will be loaded dynamically using JavaScript/jQuery -->
    </div>

    <div id="answers">
        <!-- The answers will be loaded dynamically using JavaScript/jQuery -->
    </div>

    <form id="answerForm">
        <textarea id="answer" name="answer"></textarea>
        <button type="submit">Submit</button>
    </form>

    <script>
        // Get the question ID from the URL
        const urlParams = new URLSearchParams(window.location.search);
        const questionId = urlParams.get('id');

        // Fetch the question and answers from the server
        $.get("get_question.php?id=" + questionId, function(data) {
            // Populate the question
            $("#question").html(`<h2>${data.question.question}</h2><p>Bounty: ${data.question.bounty}</p>`);

            // Populate the answers
            data.answers.forEach(answer => {
                $("#answers").append(`<p>${answer.answer}</p>`);
            });
        });

        // Handle the answer form submission
        $("#answerForm").submit(function(e) {
            e.preventDefault();

            const answer = $("#answer").val();

            // Send the answer to the server
            $.post("includes/submit_question_answer.php", { questionId, answer }, function(response) {
                if (response.success) {
                    alert("Answer submitted successfully.");
                    location.reload(); // Reload the page to show the new answer
                } else {
                    alert("Failed to submit answer.");
                }
            });
        });
    </script>
</body>

</html>

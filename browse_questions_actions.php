
<?php
session_start();

require 'db_connection.php'; // include the database connection



// Fetch user's questions
$query = $conn->prepare('SELECT * FROM Questions');
$query->bind_param('i', $_SESSION['id']);
$query->execute();
$result = $query->get_result();
$questions = $result->fetch_all(MYSQLI_ASSOC);

?>

<!-- userpage.php -->
<!DOCTYPE html>
<html>
<head>
    <title>User Page</title>
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
                    <a class="nav-link" aria-current="page" href="index.html">Home</a>
                </li>
                <?php if (!isset($_SESSION["loggedin"])): ?>
<!--                 <li class="nav-item"> -->
<!--                     <a class="nav-link" href="Register.php" title="Register">Register</a> -->
<!--                 </li> -->
                <li class="nav-item">
                    <a class="nav-link" href="Register_ReCAPTCHA.php" title="Register">Register</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="Login.php" title="Login">Login</a>
                </li>
                <?php endif; ?>
                
                <!-- pages only avaalile to the user -->
                <?php if (isset($_SESSION["loggedin"])): ?>
                <li class="nav-item">
                    <a class="nav-link" href="Question.php" title="question">Question</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="User.php" title="User">User</a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link" href="Post_Question_Form.php" title="Post Question">Post</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="Sell_Source_Code.php" title="Sell Source Code">Sell</a>
                </li>
                <?php endif; ?>
                <li class="nav-item">
                    <a class="nav-link" href="AboutUs.php" title="Register">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="Browse_Questions.php" title="Browse">Browse Questions</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

    </header>

        
        
<main>
    
<h1 class="fs-4">Welcome to SrcCode Questions Page, <?=$_SESSION['username']?>!</h1>
<?php foreach ($questions as $question): ?>
<div>
    <div class="p-5 mb-4 bg-dark rounded-3">
      <div class="container py-5">
        <h1 class="display-5 fw-bold text-white"><?= htmlspecialchars($question['title']) ?></h1>
        <p class="col-md-8 fs-4 text-white"><?= htmlspecialchars($question['body']) ?></p>
        <button class="btn btn-primary btn-lg" type="button">Answer Question</button>
      </div>
    </div>
</div>
<?php endforeach; ?>

</main>


    </body>
</html>

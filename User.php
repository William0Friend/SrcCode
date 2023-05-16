
<?php
session_start();

require 'db_connection.php'; // include the database connection

if (!isset($_SESSION['loggedin'])) {
     header('Location: Login.php');
     exit;
 }
 
 
 // Fetch logged-in user data
$userQuery = $conn->prepare('SELECT * FROM Users WHERE id = ?'); 
$userQuery->bind_param('i', $_SESSION['id']);
$userQuery->execute();
$userResult = $userQuery->get_result();
$user = $userResult->fetch_assoc();

// Fetch user's questions
$query = $conn->prepare('SELECT * FROM Questions WHERE user_id = ?');
$query->bind_param('i', $_SESSION['id']);
$query->execute();
$result = $query->get_result();
$questions = $result->fetch_all(MYSQLI_ASSOC);

// Fetch user's answers
$query = $conn->prepare('SELECT * FROM Answers WHERE user_id = ?');
$query->bind_param('i', $_SESSION['id']);
$query->execute();
$result = $query->get_result();
$answers = $result->fetch_all(MYSQLI_ASSOC);

// Rest of your HTML and PHP code
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
            </ul>
        </div>
    </div>
</nav>

    </header>

        
        
<main class="container mt-5">
<h2>Welcome to User Page, <?=$_SESSION['name']?>!</h2>
    
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <img src="https://via.placeholder.com/150" class="img-thumbnail rounded-circle mb-3" alt="User avatar">
                    <h5><?= htmlspecialchars($user['username']) ?></h5>
                    <p class="mb-0">Reputation: <?= $user['reputation'] ?></p>
                </div>
            </div>
        </div>
    
        
       
       
       <!-- In the My Questions section -->
<div class="card">
    <div class="card-header">My Questions</div>
    <div class="card-body">
        <?php foreach ($questions as $question): ?>
            <div class="mb-3">
                <h5><?= htmlspecialchars($question['title']) ?></h5>
                <p><?= htmlspecialchars($question['body']) ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- In the My Answers section -->
<div class="card">
    <div class="card-header">My Answers</div>
    <div class="card-body">
        <?php foreach ($answers as $answer): ?>
            <div class="mb-3">
                <h5>Question: <?= htmlspecialchars($answer['question_id']) ?></h5> <!-- Display question ID or title if available -->
                <p>Answer: <?= htmlspecialchars($answer['body']) ?></p>
                <pre><code><?= htmlspecialchars($answer['file']) ?></code></pre>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<div class="col-md-8">
            <div class="card">
                <div class="card-header">Logout</div>
                <div class="card-body">
                        <div class="mb-3">
   						 <p><a href="logout.php">Logout</a></p>
                        </div>
                </div>
            </div>
        </div>

    
    </div>
</main>


    </body>
</html>

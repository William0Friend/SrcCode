<?php
session_start();

require 'db_connection.php'; // include the database connection

if (!isset($_SESSION['loggedin'])) {
     header('Location: Login.php');
     exit;
 }
 
 
//Difficulties
$difficulties = array("easy","medium","hard", "not provided");
$difficultiesCount = count($difficulties);
//Levels
$difficultyLevels = array(1,2,3,4,5,-1);
$difficultyLevelsCount = count($difficultyLevels);
//Fetch programmingLanguages
$plQuery = $conn->prepare('SELECT (programming_language) FROM Programming_Language');
$plQuery->execute();
$plResult = $plQuery->get_result();
$programmingLanguages = $plResult->fetch_all(MYSQLI_ASSOC);
//Fetch tech catagories
$tcQuery = $conn->prepare('SELECT (technology_catagory) FROM Technology_Catagory');
$tcQuery->execute();
$tcResult = $tcQuery->get_result();
$technologyCatagories = $tcResult->fetch_all(MYSQLI_ASSOC);

//urgency
$questionUrgencies = array(1,2,3,4,5,6,7,8,9,10,-1);
$questionUrgenciesCount = count($questionUrgencies);


// $sql = "SELECT Questions.id, Users.username, Questions.title, Questions.body, Bounties.amount, Questions.timestamp
//         FROM Questions
//         LEFT JOIN Users ON Questions.user_id = Users.id
//         LEFT JOIN Bounties ON Questions.bounty_id = Bounties.id
//         WHERE Questions.id NOT IN (SELECT DISTINCT question_id FROM Answers)";
// $result = $conn->query($sql);

// if (!$result) {
//     die("Query failed: " . $conn->error);
// }
?>

<!-- post_question.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Post Question</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <!--Javascript-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.js" integrity="sha256-a9jBBRygX1Bh5lt8GZjXDzyOB+bWve9EiO7tROUtj/E=" crossorigin="anonymous"></script>
</head>
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
                    <a class="nav-link" href="User.php" title="User">User</a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link " href="Post_Question_Form.php" title="Post Question">Post</a>
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


<div class="container">
        <h2 class="my-3">Question Form</h2>
        
<form id="questionForm" method="POST" action="post_question_action_2.php">
        <!-- Question Title  -->
        <div class="mb-3"> 
        <h6> Question Title </h6>
        	<input type="text" id="title" name="title" placeholder="Title">
      	</div>
      	<!-- Question Body  -->
      	 
        <div class="mb-3"> 
        <h6> Question Body </h6>
        	<textarea id="body" name="body" placeholder="Question"></textarea>
     	</div>    
     	<!-- Question Bounty  -->
     	 <div class="mb-3"> 
        <h6> Question Bounty </h6>
        	<input type="number" id="bounty" name="bounty" min="0" placeholder="Bounty">
        </div>
        <!-- Difficulty -->
<div class="mb-3"> 
<h6> Difficulty </h6>     
    <select name="difficultyID">
        <?php
            $i = 0;
            while ($i < $difficultiesCount) {
                echo "<option value='".$difficulties[$i]."'>".$difficulties[$i]."</option>";
                $i++;
            }
        ?>
    </select>
</div>
<!-- Difficulty Level-->
<div class="mb-3">      
    <h6> Difficulty Level </h6>
    <select name="difficultyLevel">
        <?php
            $i = 0;
            while ($i < $difficultyLevelsCount) {
                echo "<option value='".$difficultyLevels[$i]."'>".$difficultyLevels[$i]."</option>";
                $i++;
            }
        ?>
    </select>
</div>
<!-- Programming Languages -->
<div class="mb-3">      
    <h6>Programming Languages</h6>
    <select name="programmingLanguage">
        <?php
            foreach ($programmingLanguages as $programmingLanguage) {
                echo "<option value='".$programmingLanguage['programming_language']."'>".$programmingLanguage['programming_language']."</option>";
            }
        ?>
    </select>
</div>

<!-- Tech Catagory -->
<div class="mb-3">
<h6> Tech Catagory </h6>      
    <select name="techCatagory">
        <?php
            foreach ($technologyCatagories as $technologyCatagory) {
                echo "<option value='".$technologyCatagory['technology_catagory']."'>".$technologyCatagory['technology_catagory']."</option>";
            }
        ?>
    </select>
</div>

<!-- Question Urgency -->
<div class="mb-3">
<h6> Question Urgency </h6>      
    <select name="questionUrgency">
        <?php
            $i = 0;
            while ($i < $questionUrgenciesCount) {
                echo "<option value='".$questionUrgencies[$i]."'>".$questionUrgencies[$i]."</option>";
                $i++;
            }
        ?>
    </select>
</div>

        <!-- Question Notes  -->
        <div class="mb-3"> 
        <h6>  Question Notes </h6>
        	<textarea id="notes" name="notes" placeholder="Notes, or anything else you think will be relevant..."></textarea>
     	</div>    
     	<!-- Post Question Button -->
     	<div class="mb-3"> 
        	<button type="submit">Post</button>
    	</div>
    	
    </form>
</div>
    </main>
    
    
 <div class="container">
        <footer class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4 border-top">
            <p class="col-md-4 mb-0 text-muted">© 2023 Company, Inc</p>

            <!--<a href="/" class="col-md-4 d-flex align-items-center justify-content-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none">
                <svg class="bi me-2" width="40" height="32"><use xlink:href="#bootstrap"></use></svg>
            </a>-->

            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-currency-dollar" viewBox="0 0 16 16">
                <path d="M4 10.781c.148 1.667 1.513 2.85 3.591 3.003V15h1.043v-1.216c2.27-.179 3.678-1.438 3.678-3.3 0-1.59-.947-2.51-2.956-3.028l-.722-.187V3.467c1.122.11 1.879.714 2.07 1.616h1.47c-.166-1.6-1.54-2.748-3.54-2.875V1H7.591v1.233c-1.939.23-3.27 1.472-3.27 3.156 0 1.454.966 2.483 2.661 2.917l.61.162v4.031c-1.149-.17-1.94-.8-2.131-1.718H4zm3.391-3.836c-1.043-.263-1.6-.825-1.6-1.616 0-.944.704-1.641 1.8-1.828v3.495l-.2-.05zm1.591 1.872c1.287.323 1.852.859 1.852 1.769 0 1.097-.826 1.828-2.2 1.939V8.73l.348.086z" />
            </svg>

            <ul class="nav col-md-4 justify-content-end">
                <li class="nav-item"><a href="Index.html" class="nav-link px-2 text-muted">Home</a></li>
                <li class="nav-item"><a href="Features.html" class="nav-link px-2 text-muted">Features</a></li>
                <li class="nav-item"><a href="Pricing.html" class="nav-link px-2 text-muted">Pricing</a></li>
                <li class="nav-item"><a href="FAQ.html" class="nav-link px-2 text-muted">FAQs</a></li>
                <li class="nav-item"><a href="AboutUs.html" class="nav-link px-2 text-muted">About</a></li>
            </ul>
        </footer>
    </div>    
</body>
</html>


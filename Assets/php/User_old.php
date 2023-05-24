
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
//$user = $userResult->fetch_assoc();
$user = $userResult->fetch_all(MYSQLI_ASSOC);

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
	<!-- Bootstrap Select -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
	
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

    <?php


// Get images from the database
$query = $conn->query("SELECT * FROM images ORDER BY uploaded_on DESC");
/* Get the number of rows in the result set */
$row_cnt = mysqli_num_rows($query);
//display all
//if($query->num_rows > 0){
if($query->num_rows > 0){
    while($row = $query->fetch_assoc()){
        $imageURL = 'uploads/'.$row["file_name"];
?>


<main class="container mt-5">
 <script>
 
  $( function() {
    $( "#accordion" ).accordion();
  } );

  <!-- initialize Bootstrap Select  -->
  $(document).ready(function () {
      $('.selectpicker').selectpicker();
  });
  
  </script>
<h2>Welcome to User Page, <?=$_SESSION['username']?>!</h2>
	
    
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                     <img src="<?php echo $imageURL; ?>" class="img-thumbnail rounded-circle mb-3" alt="User avatar">                       
                        <?php }
                                }else{ ?>
                                    <p>No image(s) found...</p>
                                <?php } ?>
        
<!--                     <form action="upload_image_action.php" method="post" enctype="multipart/form-data"> -->
<!--                        Update Your Profile Picture: -->
<!--                       <input type="file" name="fileToUpload" id="fileToUpload"> -->
<!--                       <input type="submit" value="Upload Image" name="submit"> -->
<!--                		</form> -->
               		<form action="upload_user_image.php" method="post" enctype="multipart/form-data">
                        Select Image File to Upload for your Profile Picture:
                        <input type="file" name="file">
                        <input type="submit" name="submit" value="Upload">
                    </form>
                                      
                    <h5><?= htmlspecialchars($user['username']) ?></h5>
                    <p class="mb-0">Reputation: <?= $user['reputation'] ?></p>
                </div>
            </div>
        </div>
    </div>
       
       
       <!-- In the My Questions section -->
    <div id="accordion">
    <h3>My Questions</h3>
  
<!-- <div class="card"> -->
<!--     <div class="card-header">My Questions</div> -->
<!--     <div class="card-body"> -->
        <?php foreach ($questions as $question): ?>
            <div class="mb-3">
                <h5><?= htmlspecialchars($question['title']) ?></h5>
                <p><?= htmlspecialchars($question['body']) ?></p>
            </div>
        <?php endforeach; ?>
    <!-- </div>
</div> -->
<!-- selection dropdown  -->
<div class="card text-center">
    <div class="card-header">Update and Delete my Questions</div>
    <div class="card-body">
        <form method="POST" action="update_delete_question.php" id="question_form">
            <select name="question_id" class="selectpicker">
                <?php foreach ($questions as $question): ?>
                    <option value="<?= $question['id'] ?>"><?= htmlspecialchars($question['title']) ?></option>
                <?php endforeach; ?>
            </select>
            <input type="text" name="new_title" placeholder="New Title">
            <textarea name="new_body" placeholder="New Body"></textarea>
            <input type="submit" name="update" value="Update Question">
            <input type="submit" name="delete" value="Delete Question">
        </form>
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

<!-- selection dropdown  -->
<div class="card text-center">
    <div class="card-header">Update and Delete my Answers</div>
    <div class="card-body">
        <form method="POST" action="update_delete_answer.php" id="answer_form">
            <select name="answer_id" class="selectpicker">
                <?php foreach ($answers as $answer): ?>
                    <option value="<?= $answer['id'] ?>"><?= "Question ID: " . htmlspecialchars($answer['question_id']) . ", Answer: " . htmlspecialchars($answer['body']) ?></option>
                <?php endforeach; ?>
            </select>
            <textarea name="new_body" placeholder="New Body"></textarea>
            <input type="submit" name="update" value="Update Answer">
            <input type="submit" name="delete" value="Delete Answer">
        </form>
    </div>
</div>


<!-- logout  -->
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

<h2> Update or Delete your Profile</h2>
<div class="card text-center">
    <div class="card-body">
        <form method="POST" action="update_delete_user.php">
            <input type="text" name="new_username" placeholder="New Username">
            <input type="text" name="new_email" placeholder="New Email">
            <input type="submit" name="update" value="Update User Info">
            <input type="submit" name="delete" value="Delete Account">
        </form>
      </div>
</div>
</main>



    </body>
    
     <!--Footer-->
    <div class="container">
        <footer class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4 border-top">
            <p class="col-md-4 mb-0 text-muted">© 2023 Company, Inc</p>

           
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
</html>

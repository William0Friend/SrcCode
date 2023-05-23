<?php
?>
<!DOCTYPE html>
<html>
<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
<!--Javascript-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.6.4.js" integrity="sha256-a9jBBRygX1Bh5lt8GZjXDzyOB+bWve9EiO7tROUtj/E=" crossorigin="anonymous"></script></head>
<!-- Client side validation -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://unpkg.com/commentbox.io/dist/commentBox.min.js"></script>
<script>
$( function() {
    $( "#accordion" ).accordion({
        collapsible: true,
        active: false
    });
} );
</script>
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
<?php
require 'db_connection.php';

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM Questions WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        echo"<div class=\"container my-5\">";
        echo "<div class=\"p-5 text-center bg-body-tertiary rounded-3 shadow-lg\">";
        echo "<svg class=\"bi mt-4 mb-3\" style=\"color: var(--bs-indigo);\" width=\"100\" height=\"100\"><use xlink:href=\"#bootstrap\"></use></svg>";
        echo "<h1 class=\"text-body-emphasis\">Question</h1>";
                    echo "<h3>" . htmlspecialchars($row["title"]) . "</h3>";
                    echo "<div>";
                    echo "<p>" . htmlspecialchars($row["body"]) . "</p>";
                    echo "</div>";
        echo "<div class=\"d-inline-flex gap-2 mb-5\">";
        echo "<button class=\"d-inline-flex align-items-center btn btn-primary btn-lg px-4 rounded-pill\" type=\"button\">";
        echo "Answer";
        echo "<svg class=\"bi ms-2\" width=\"24\" height=\"24\"><use xlink:href=\"#arrow-right-short\"></use></svg>";
        echo "</button>";
        echo "</div>";
        echo "</div>";
        echo "</div>";
                    
        
        $stmt = $conn->prepare("SELECT * FROM Answers WHERE question_id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $answers = $stmt->get_result();
        
        if ($answers->num_rows > 0) {
            while($answer_row = $answers->fetch_assoc()) {
                
                echo "<h3 class=\"text-center\">Answer</h3>";
                echo "<div>";
                echo "<p class=\"text-center\">" . htmlspecialchars($answer_row["body"]) . "</p>";
                echo "<div id='answer-".$answer_row['id']."' class='commentbox'></div>";
                echo "</div>";
                echo "<script>commentBox('answer-".$answer_row['id']."');</script>";
                
                
            }
        } else {
            echo "<h3>No answers yet.</h3>";
            echo "<div>";
            echo "<p>Be the first to answer this question!</p>";
            //echo button to bring user to anser page with this question pre filled out
            //echo or take the user to the login page, since user must be logged in to answer a question
            echo "</div>";
        }
    } else {
        echo "Question not found.";
    }
} else {
    echo "Invalid request.";
}
?>

<div class="container">
	<a href="Browse_Questions.php"><button class="btn btn-danger">Back to Questions</button></a>
	
</div>


 <!--Footer-->
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

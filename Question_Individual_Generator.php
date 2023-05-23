<!DOCTYPE html>
<html>
<head>
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
        echo "<h3>" . htmlspecialchars($row["title"]) . "</h3>";
        echo "<div>";
        echo "<p>" . htmlspecialchars($row["body"]) . "</p>";
        echo "</div>";
        
        $stmt = $conn->prepare("SELECT * FROM Answers WHERE question_id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $answers = $stmt->get_result();
        
        if ($answers->num_rows > 0) {
            while($answer_row = $answers->fetch_assoc()) {
                echo "<h3>Answer</h3>";
                echo "<div>";
                echo "<p>" . htmlspecialchars($answer_row["body"]) . "</p>";
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
	<a href="Browse_Questions.php"><button>Back to Questions</button></a>
	
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

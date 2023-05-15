<?php
session_start();

if (!isset($_SESSION['loggedin'])) {
    header('Location: Login.php');
    exit;
}

$servername = "localhost";
$username = "Will";
$password = "N0th1ng4u";
$dbname = "srccode0";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
    
if (mysqli_connect_errno()) {
    exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

// Get user's questions
$sql = "SELECT * FROM Questions WHERE user_id = ".$_SESSION['user_id'];
$result = $conn->query($sql);

if (!$result) {
    die("Query failed: " . $conn->error);
}

if ($result->num_rows > 0) {
    $questions = "";
    while($row = $result->fetch_assoc()) {
        // Sanitize output data
        $id = htmlspecialchars($row["id"]);
        $title = htmlspecialchars($row["title"]);
        $body = htmlspecialchars($row["body"]);
        $bounty = htmlspecialchars($row["bounty"]);
        $timestamp = htmlspecialchars($row["timestamp"]);
        
        // Get answers for this question
        $answersSql = "SELECT * FROM Answers WHERE question_id = ".$id;
        $answersResult = $conn->query($answersSql);
        $answers = "";
        if ($answersResult->num_rows > 0) {
            while($answerRow = $answersResult->fetch_assoc()) {
                // Sanitize output data
                $answerId = htmlspecialchars($answerRow["id"]);
                $answerBody = htmlspecialchars($answerRow["body"]);
                $answerTimestamp = htmlspecialchars($answerRow["timestamp"]);
                $answers .= "<p>Answer: ".$answerBody."<button data-answer-id='".$answerId."'>Select this answer</button></p>";
            }
        } else {
            $answers = "No answers yet.";
        }

        $questions .= "<div><h3>".$title."</h3><p>".$body."</p>".$answers."</div>";
    }
} else {
    $questions = "You haven't asked any questions yet.";
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Your Questions</title>
    <!-- Add your CSS and JS files here -->
</head>
<body>
    <?php echo $questions; ?>
    <script>
        // When a "Select this answer" button is clicked
        $("button").click(function() {
            var answerId = $(this).data("answer-id");

            // Redirect to the payment page with the selected answer ID
            window.location.href = "payment.php?answerId=" + answerId;
        });
    </script>
</body>
</html>

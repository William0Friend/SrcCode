	

<?php
session_start();
require 'db_connection.php';
if (mysqli_connect_errno()) {
    exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

$sql = "SELECT Questions.id, Users.username, Questions.title, Questions.body, Bounties.amount, Questions.timestamp
        FROM Questions
        LEFT JOIN Users ON Questions.user_id = Users.id
        LEFT JOIN Bounties ON Questions.bounty_id = Bounties.id
        WHERE Questions.id NOT IN (SELECT DISTINCT question_id FROM Answers)";
$result = $conn->query($sql);

if (!$result) {
    die("Query failed: " . $conn->error);
}

if ($result->num_rows > 0) {
    $div_data = "";
    while($row = $result->fetch_assoc()) {
//         $id = htmlspecialchars($row["id"]);
        $username = htmlspecialchars($row["username"]);
        $title = htmlspecialchars($row["title"]);
        $body = htmlspecialchars($row["body"]);
        $bounty = htmlspecialchars($row["amount"]);  // Now, this represents bounty fetched from Bounties table
        $timestamp = htmlspecialchars($row["timestamp"]);
        
        $div_data .= "<div class=\"container bg-dark text-white p-5 mb-4 rounded-3 shadow-lg\">Username:   " . $username. "<br/>Question Title:   " . $title. "<br/>Question Body:   " . $body. "<br/>Question Bounty:   " . $bounty. "<br/>Question Submission Date:   " . $timestamp. "<br/><button class=\"btn btn-danger text-white align-center\">Answer Question</button></div>";
        //$div_data += "<button class=\"btn btn-success\">Answer Question<button></div>";
    }
} else {
    $div_data = "<div colspan='6'>No unanswered questions.</div>";
}

// Datatables

$sql = "SELECT Questions.id, Users.username, Questions.title, Questions.body, Bounties.amount AS bounty, Questions.timestamp
        FROM Questions
        LEFT JOIN Users ON Questions.user_id = Users.id
        LEFT JOIN Bounties ON Questions.bounty_id = Bounties.id
        WHERE Questions.id NOT IN (SELECT DISTINCT question_id FROM Answers)";
$result = $conn->query($sql);

// Fetch all rows into an array
$questions = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <!--Javascript-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.js" integrity="sha256-a9jBBRygX1Bh5lt8GZjXDzyOB+bWve9EiO7tROUtj/E=" crossorigin="anonymous"></script></head>
<!-- DataTables CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.css">
<!-- DataTables JS -->
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.js"></script>
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
                    <a class="nav-link active" href="Browse_Questions.php" title="Browse">Browse Questions</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

    </header>
    
<main>

<!-- Unanswered questions table jquery version -->
<table id="questionsTable" class="display">
    <thead>
        <tr>
            <th>Username</th>
            <th>Title</th>
            <th>Body</th>
            <th>Bounty</th>
            <th>Timestamp</th>
            <th>Actions</th>
            <th>Q & A</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($questions as $question): ?>
            <tr>
                <td><?php echo htmlspecialchars($question["username"]); ?></td>
                <td><a href="Question_Individual_Generator.php?id=<?php echo htmlspecialchars($question["id"]); ?>"><?php echo htmlspecialchars($question["title"]); ?></a></td>
                <td><?php echo htmlspecialchars($question["body"]); ?></td>
                <td><?php echo htmlspecialchars($question["bounty"]); ?></td>
                <td><?php echo htmlspecialchars($question["timestamp"]); ?></td>
                <td>
                	<a href="Question_Individual_Generator.php?id=<?php echo htmlspecialchars($question["id"]); ?>" class="btn btn-danger">Question</a></td>
            	<td>
				    <button type="button" class="btn btn-primary sellButton" data-bs-toggle="modal" data-bs-target="#sellSourceCodeModal" data-question-id="<?php echo htmlspecialchars($question["id"]); ?>">Answer</button>
				</td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<div class="modal fade" id="sellSourceCodeModal" tabindex="-1" aria-labelledby="sellSourceCodeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sellSourceCodeModalLabel">Sell Source Code</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="sellSourceCodeForm" method="POST" action="sell_source_code_action_2.php" class="mt-3">
                    <input type="hidden" id="questionIdInput" name="questionId" />
                    <h6 class="mb-2">Sell Source Code:</h6>
                    <input type="text" id="title" name="title" placeholder="Source Code Title" class="form-control" />
                    <textarea id="description" name="description" placeholder="Source Code Description" class="form-control mt-2" rows="4"></textarea>
                    <textarea id="code" name="code" placeholder="Paste your source code here" class="form-control mt-2" rows="6"></textarea>
                    <button type="submit" class="btn btn-secondary mt-2">Sell</button>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- call thhe dataTable  -->
<script>
//create answer button
$(document).ready(function() {
	//call the datatable
	$('#questionsTable').DataTable();

    $('.sellButton').on('click', '.btn-primary', function(event) {
        <?php if(isset($_SESSION['loggedin'])): ?>
        var questionId = $(this).data('question-id');
        $('#questionIdInput').val(questionId);
        <?php else: ?>
            // Redirect to login page
            window.location.href = 'Login.php';
        <?php endif; ?>
    });
});
// $(document).ready(function() {
//     $('#questionsTable').DataTable();

//     $('.sellButton').on('click', function() {
//         var questionId = $(this).data('question-id');
//         $('#questionIdInput').val(questionId);
//     });
// });

</script>



 </main> 

    </body>
    
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
</html>


<?php

// Start the session
session_name('srccode');
session_start();
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$servername = "localhost";
$username = "root";
$password = "N0th1ng4u";
$dbname = "srccode0";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the submitted form data
$username = mysqli_real_escape_string($conn, htmlspecialchars($_POST['username']));
$password = mysqli_real_escape_string($conn, htmlspecialchars($_POST['password']));

// Retrieve the user's data from the database
$sql = "SELECT * FROM Users WHERE username = '$username'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        // Verify the password
        if (password_verify($password, $row["password"])) {
            // Password is correct, start the session
            $_SESSION["loggedin"] = true;
            $_SESSION["username"] = $username;
            echo json_encode(['success' => true, 'message' => 'Login successful.']);
        } else {
            // Password is incorrect
            echo json_encode(['success' => false, 'message' => 'Password is incorrect.']);
        }
    }
} else {
    // No user found with that username
    echo json_encode(['success' => false, 'message' => 'No user found with that username.']);
}

$conn->close();
?>

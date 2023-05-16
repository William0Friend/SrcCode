<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_name('srcode');
session_start();

require 'db_connection.php';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$secret_key = "6LeX8QgmAAAAADSC3W12EZ85WrP0DSzzC42pGO9H"; // Replace with your actual secret key
$recaptcha_response = $_POST['g-recaptcha-response'];

// Verify the reCAPTCHA response
$verify_response = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret_key.'&response='.$recaptcha_response);
$response_data = json_decode($verify_response);

if ($response_data->success) {
    // If the reCAPTCHA is valid, proceed with registering the user
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    
    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    $sql = "INSERT INTO Users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";
    
    if ($conn->query($sql) === TRUE) {
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;
        header("Location: User.php"); // Redirect user to Users.php
        exit;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    echo "Invalid reCAPTCHA. Please try again.";
}

$conn->close();

?>


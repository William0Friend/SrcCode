<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//name session
session_name('srcode');
//start session
session_start();


require 'db_connection.php';

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Add reCAPTCHA validation here.
$recaptchaResponse = $_POST['g-recaptcha-response'];

$response=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6LeX8QgmAAAAADSC3W12EZ85WrP0DSzzC42pGO9H&response=".$recaptchaResponse."&remoteip=".$_SERVER['REMOTE_ADDR']);

$obj = json_decode($response);
if(!$obj->success){
    echo json_encode(['success' => false, 'message' => 'reCAPTCHA verification failed.']);
    return;
}

$username = mysqli_real_escape_string($conn, $_POST['username']);
$email = mysqli_real_escape_string($conn, $_POST['email']);
$password = mysqli_real_escape_string($conn, $_POST['password']);

// Hash the password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO Users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

if ($conn->query($sql) === TRUE) {
    $last_id = $conn->insert_id;
    echo json_encode(['success' => true, 'message' => 'Registration successful.', 'userId' => $last_id]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $sql . '<br>' . $conn->error]);
}

$conn->close();


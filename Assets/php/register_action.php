<?php

//require 'db_connect.php';
// register_action.php
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

$username = mysqli_real_escape_string($conn, $_POST['username']);
$email = mysqli_real_escape_string($conn, $_POST['email']);
$password = mysqli_real_escape_string($conn, $_POST['password']);

// Hash the password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);
// print it out for success
echo "Hashed password: " . $hashed_password;



$sql = "INSERT INTO Users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

if ($conn->query($sql) === TRUE) {
  echo json_encode(['success' => true, 'message' => 'Registration successful.']);
} else {
  echo json_encode(['success' => false, 'message' => 'Error: ' . $sql . '<br>' . $conn->error]);
}

$conn->close();


    // Process the login...
// If the login is successful:
$_SESSION['loggedin'] = true;
$_SESSION['username'] = $username;  // only if $username is set with the user's username


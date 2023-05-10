<?php
// Include the database configuration file
include_once 'config.php';

// Retrieve the username and password from the POST request
$username = $_POST['username'];
$password = $_POST['password'];

// Define the SQL query to retrieve the user with the specified username and password
$sql = "SELECT * FROM User WHERE username='$username' AND password='$password'";

// Execute the query and retrieve the result
$result = $conn->query($sql);
$user = $result->fetch_object();

// If the user exists, generate a JWT and return it
if ($user) {
    $payload = array(
        'sub' => $user->id,
        'username' => $user->username
    );
    $jwt = JWT::encode($payload, $secret_key);
    // Return the JWT as a JSON object
    echo json_encode(array('token' => $jwt));
} else {
    // If the user does not exist, return an error message
    echo json_encode(array('error' => 'Invalid username or password'));
}
?>
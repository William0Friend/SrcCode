<?php
// register.php
require 'config.php'; // Include your database configuration

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $user_type = $_POST['user_type'];

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare and execute the SQL query
    $stmt = $pdo->prepare('INSERT INTO users (username, email, password, user_type) VALUES (?, ?, ?, ?)');
    $result = $stmt->execute([$username, $email, $hashed_password, $user_type]);

    // Return the result as JSON
    header('Content-Type: application/json');
    echo json_encode(['success' => $result, 'message' => $result ? 'Registration successful!' : 'An error occurred.']);
}

// login.php
require 'config.php'; // Include your database configuration

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare and execute the SQL query
    $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ?');
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    // Check if the user exists and the password is correct
    if ($user && password_verify($password, $user['password'])) {
        // Start the session and set the user ID
        session_start();
        $_SESSION['user_id'] = $user['id'];

        // Return success
        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
    } else {
        // Return an error message
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Invalid username or password.']);
    }
}
?>
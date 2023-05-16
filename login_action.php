<?php
session_start();

require 'db_connection.php';

if (mysqli_connect_errno()) {
    exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

if (!isset($_POST['username'], $_POST['password'])) {
    exit('Please fill both the username and password fields!');
}

if ($stmt = $conn->prepare('SELECT id, password FROM Users WHERE username = ?')) {
    $stmt->bind_param('s', $_POST['username']);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $password);
        $stmt->fetch();
        
        if (password_verify($_POST['password'], $password)) {
            $_SESSION['loggedin'] = TRUE;
            $_SESSION['username'] = $_POST['username'];
            $_SESSION['id'] = $id;
            header('Location: User.php');
            exit;
        } else {
            header('Location: Login.php?error=Incorrect password!');
            exit;
        }
    } else {
        header('Location: Login.php?error=Incorrect username!');
        exit;
    }
    
    $stmt->close();
} else {
    header('Location: Login.php?error=Could not prepare statement!');
    exit;
}
?>
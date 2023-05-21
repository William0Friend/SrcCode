<?php
session_start();
require 'db_connection.php'; 

if (!isset($_SESSION['loggedin'])) {
     header('Location: Login.php');
     exit;
}

$new_username = $_POST['new_username'];
$new_email = $_POST['new_email'];

if (isset($_POST['update'])) {
    if (!empty($new_username) && !empty($new_email)) {
        $update = $conn->prepare("UPDATE Users SET username=?, email=? WHERE id=?");
        $update->bind_param("ssi", $new_username, $new_email, $_SESSION['id']);
        $update->execute();

        if ($update->affected_rows > 0) {
            session_destroy();
            header('Location: Login.php');
            echo "User info updated successfully!";
        } else {
            echo "Update failed.";
        }
    } else {
        echo "Please enter a new username and email.";
        session_destroy();
        header('Location: Login.php');
        
    }
}

if (isset($_POST['delete'])) {
    $delete = $conn->prepare("DELETE FROM Users WHERE id=?");
    $delete->bind_param("i", $_SESSION['id']);
    $delete->execute();

    if ($delete->affected_rows > 0) {
        echo "Account deleted successfully!";
        session_destroy();
        header('Location: Login.php');
    } else {
        echo "Deletion failed.";
    }
}
?>

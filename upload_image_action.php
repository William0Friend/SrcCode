
<?php
$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));


///////////////////////////////////COPY OVER
// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
}

// Check if file already exists
if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
}

// Check file size
if ($_FILES["fileToUpload"]["size"] > 500000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}

// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
    
    ///////////////////////////////////COPY OVER END
    
    
    
// if everything is ok, try to upload file
} else {
//     if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
//         echo "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.";
//         // Now you can insert this path into the database
//         $user_id = $_SESSION['id']; // Assuming you're using PHP session to store logged in user's id
//         $image_path = $target_file;
//         insertUserImage($user_id, $image_path);
//     } else {
//         echo "Sorry, there was an error uploading your file.";
//     }
    
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.";
        // Now you can insert this image into the database
        $user_id = $_SESSION['id']; // Assuming you're using PHP session to store logged in user's id
        $image_data = file_get_contents($target_file);
        insertUserImage($user_id, $image_data);
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
    
}


// Function to insert image into UserImage table
function insertUserImage($user_id, $image_data) {
//     // Database connection
//     $servername = "localhost";
//     $username = "root";
//     $password = "N0th1ng4u";
//     $dbname = "srccode0";
    
//     // Create connection
//     $conn = new mysqli($servername, $username, $password, $dbname);
    require 'db_connection.php';
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // Prepare SQL statement
    $stmt = $conn->prepare("INSERT INTO UserImage (user_id, image_data) VALUES (?, ?)");
    $stmt->bind_param("ib", $user_id, $image_data);
    
    // Execute the statement
    if ($stmt->execute()) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $stmt->error;
    }
    
    // Close connection
    $stmt->close();
    $conn->close();
}

?>

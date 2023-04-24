
<?php
#used to display a user's profile page and retrieve their code snippets from a MySQL database:


// Connect to the MySQL database
$db = new mysqli('hostname', 'username', 'password', 'myDatabase');

// Check for a successful connection
if ($db->connect_error) {
    die('Connection failed: ' . $db->connect_error);
}

// Get the user's ID (change to be retrieved from a session variable or URL parameter)
$user_id = 1;

// Create a prepared statement to retrieve the user's code snippets from the database
$stmt = $db->prepare('SELECT code FROM snippets WHERE user_id = ?');
$stmt->bind_param('i', $user_id);
$stmt->execute();
$stmt->bind_result($code);

// Display the user's profile page
echo '<h1>Username\'s Profile</h1>';
echo '<h2>My Code Snippets</h2>';
echo '<ul>';

// Loop through the retrieved code snippets and display them on the page
while ($stmt->fetch()) {
    echo '<li>' . htmlspecialchars($code) . '</li>';
}

echo '</ul>';

// Close the prepared statement and database connection
$stmt->close();
$db->close();

#script connects to MySQL database, retrieves the code snippets for a specific user
# (in this case, with an ID of 1) using a prepared statement. 
#The script then displays the user's profile page and loops through 
#the retrieved code snippets to display them on the page.
?>




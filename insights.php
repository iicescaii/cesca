<?php
// Database connection
$servername = "localhost";
$username = "root";  // Default username for XAMPP
$password = "";      // Default password for XAMPP (empty)
$dbname = "my_database"; // Your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $name = $_POST['name'];
    $date = $_POST['date'];
    $comment = $_POST['comment'];

    // Insert the data into the database
    $sql = "INSERT INTO residents_insights (name, date, comment) 
            VALUES ('$name', '$date', '$comment')";

    if ($conn->query($sql) === TRUE) {
        header("Location: residentsubmisison.html");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>

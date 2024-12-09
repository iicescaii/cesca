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
    $complainant = $_POST['complainant'];
    $accused = $_POST['accused'];
    $incident_type = $_POST['incident_type'];
    $incident_address = $_POST['incident_address'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $message = $_POST['message'];

    // Insert the data into the database
    $sql = "INSERT INTO incident_reports (complainant, accused, incident_type, incident_address, date, time, message) 
            VALUES ('$complainant', '$accused', '$incident_type', '$incident_address', '$date', '$time', '$message')";

    if ($conn->query($sql) === TRUE) {
        header("Location: incidentsubmission.html");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>

<?php
session_start();

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "attendance";

// Create a connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Debugging: Check if form data is being received
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['query_id']) && isset($_POST['status'])) {
    $query_id = $_POST['query_id'];
    $status = $_POST['status'];
    $action_time = date("Y-m-d H:i:s"); // Capture the current timestamp

    // Debugging: Print received data
    echo "Received Query ID: " . $query_id . "<br>";
    echo "Received Status: " . $status . "<br>";

    // Update the query status in the database
    $update_query = "UPDATE queries SET status = ?, action_taken_time = ? WHERE query_id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("sss", $status, $action_time, $query_id);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }

    $stmt->close();
} else {
    echo "invalid_request";
}

$conn->close();
?>

<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "attendance";

$conn = mysqli_connect($servername, $username, $password, $database);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if the Register No is passed
if (isset($_POST['register_no'])) {
    $register_no = $_POST['register_no']; // Get the Register No from POST request

    // Update the Status field to 'Verified' in the student_user table
    $query = "UPDATE student_user SET Status = 'Verified' WHERE RegisterNo = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $register_no);
    $stmt->execute();

    // Check if the update was successful
    if ($stmt->affected_rows > 0) {
        // Set session variable to indicate the data has been verified
        $_SESSION['data_verified'] = true;
        echo "Your data has been successfully verified.";
    } else {
        echo "An error occurred. Please try again.";
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>

<?php
session_start();

// Database connection
$host = "localhost"; // Change if needed
$username = "root"; // Default for XAMPP
$password = ""; // Default for XAMPP (empty)
$database = "attendance";

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_input = trim($_POST['username']); // Can be username or email
    $password = $_POST['password'];

    // Validate password complexity
    if (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password)) {
        echo "<script>alert('Password must be at least 8 characters with at least 1 uppercase, 1 lowercase, 1 number, and 1 special character.'); window.location.href='admin_login.html';</script>";
        exit();
    }

   // SQL query to check if username/email exists
$sql = "SELECT * FROM admin_user WHERE (username = ? OR email = ?) AND password = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $user_input, $user_input, $password);
$stmt->execute();
$result = $stmt->get_result();

// If user exists, login success
if ($result->num_rows == 1) {
    // Fetch the admin data
    $user = $result->fetch_assoc();
    
    // Set session variables
    $_SESSION['admin_logged_in'] = true;
    $_SESSION['admin_username'] = $user_input;
    $_SESSION['admin_name'] = $user['Name'];  // Correctly reference the user data

    // Redirect to dashboard
    header("Location: admin_dashboard.php");
    exit();
} else {
    echo "<script>alert('Invalid username/email or password. Please try again.'); window.location.href='admin_login.html';</script>";
    exit();
}

}
// Close connection
$conn->close();
?>

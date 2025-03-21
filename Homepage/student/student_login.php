<?php
session_start();

// Database connection details
$servername = "localhost"; // Database server (usually localhost)
$username = "root"; // Your MySQL username
$password = ""; // Your MySQL password (empty if you haven't set one)
$dbname = "attendance"; // Your database name (update if your database has a different name)

// Create a connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query to check if username or email exists
    $query = "SELECT * FROM student_user WHERE UserName = ? OR Email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $username, $username); // Bind both username and email
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if a matching record is found
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Check if password matches
        if ($user['Password'] == $password) {
            // Correct credentials, set session and store all relevant data in session
            $_SESSION['student_name'] = $user['Name']; // Store student's name in session
            $_SESSION['register_no'] = $user['RegisterNo']; // Store Register No in session
            $_SESSION['password'] = $user['Password'];  // Store student ID in session (if needed)
            

            // Redirect to student dashboard
            header("Location: student_dashboard.php");
            exit;
        } else {
            // Incorrect password
            $error_message = "Invalid Password!";
        }
    } else {
        // No matching user found
        $error_message = "Invalid Username or Email!";
    }
}
?>

<?php
// Display error message if any
if (isset($error_message)) {
    echo "<p style='color: red;'>$error_message</p>";
}
?>

<!-- Your login form goes here -->

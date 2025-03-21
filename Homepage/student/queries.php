<?php
// Start session to access logged-in user data
session_start();


// Get student details from the session
$register_no = $_SESSION['register_no']; // Student's register number
$student_name = $_SESSION['student_name']; // Student's name

// Database connection
$servername = "localhost";
$username = "root"; // Your MySQL username
$password = ""; // Your MySQL password
$dbname = "attendance"; // Your database name

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']); // Student enters email manually
    $subject = mysqli_real_escape_string($conn, $_POST['subject']);
    $query_description = mysqli_real_escape_string($conn, $_POST['query']);

    // Handle file upload (optional)
    $file_path = NULL;
    if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
        $file_name = $_FILES['file']['name'];
        $file_tmp = $_FILES['file']['tmp_name'];
        $upload_dir = "uploads/";
        
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $file_path = $upload_dir . basename($file_name);
        if (!move_uploaded_file($file_tmp, $file_path)) {
            die("File upload failed.");
        }
    }

    // Insert query into the database
    $sql = "INSERT INTO queries (register_no, student_name, email, subject, query_description, file_path, status, query_raised_time)
            VALUES ('$register_no', '$student_name', '$email', '$subject', '$query_description', '$file_path', 'Pending', NOW())";

    if ($conn->query($sql) === TRUE) {
        $success_msg = "Your query has been submitted successfully!";
    } else {
        $error_msg = "Error: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/queries.css">
    <title>Raise Query</title>
</head>
<body>

    <div class="sidebar">
        <h2 style= "color:white; text-align:left;">Menu</h2>
        <ul>
            <li><a href="student_dashboard.php">Dashboard</a></li>
            <li><a href="queries.php">Query</a></li>
            <li><a href="check_query_status.php">Query Status</a></li>
            <li><a href="settings.php">Settings</a></li>
            <li><a href="student_logout.php">Logout</a></li>
        </ul>
    </div>

<div class="query-container">
    <h2>Raise a Query</h2>
    <?php if (isset($success_msg)) echo "<p style='color: green;'>$success_msg</p>"; ?>
    <?php if (isset($error_msg)) echo "<p style='color: red;'>$error_msg</p>"; ?>

    <form action="" method="post" enctype="multipart/form-data">
        <label>Register No:</label>
        <input type="text" name="register_no" value="<?= $register_no ?>" readonly><br>

        <label>Student Name:</label>
        <input type="text" name="student_name" value="<?= $student_name ?>" readonly><br>

        <label>Email:</label>
        <input type="email" name="email" required><br>

        <label>Subject:</label>
        <input type="text" name="subject" required><br>

        <label>Query Description:</label>
        <textarea name="query" required></textarea><br>

        <label>Attach File (optional):</label>
        <input type="file" name="file"><br>

        <button type="submit">Submit Query</button>
    </form>
</div>
</body>
</html>

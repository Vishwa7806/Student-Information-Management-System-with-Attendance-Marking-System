<?php
// Database connection details
$host = "localhost"; // Change if not using localhost
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$database = "attendance"; // Your database name

// Connect to the database
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve form data
$staff_id = $_POST['staff_id']; // Not used in the SQL statement
$staff_name = $_POST['staff_name'];
$username = $_POST['username'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$password = $_POST['password']; // Using plain password here
$department_id = $_POST['department_id'];
$department_name = $_POST['department_name'];
$designation = $_POST['designation'];

// Insert data into the database
$sql = "INSERT INTO staff_user (StaffName, UserName, Email, PhoneNo, Password, DepartmentID, DepartmentName, Designation)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssssss", $staff_name, $username, $email, $phone, $password, $department_id, $department_name, $designation);

if ($stmt->execute()) {
    echo "Staff user created successfully!";
    echo "<br><a href='add_staff.html'>Create another user</a>";
} else {
    echo "Error: " . $stmt->error;
}

// Close the connection
$stmt->close();
$conn->close();
?>

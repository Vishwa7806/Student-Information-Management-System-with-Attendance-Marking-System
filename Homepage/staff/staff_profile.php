<?php
session_start();

// Check if username exists in session
if (!isset($_SESSION['username'])) {
    die("Access Denied: Please log in first.");
}

// Get username from session
$username = $_SESSION['username'];

// Database connection
$servername = "localhost";
$username_db = "root";
$password_db = "";
$dbname = "attendance";

$conn = new mysqli($servername, $username_db, $password_db, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare and execute query
$query = "SELECT ID, StaffName, UserName, Email, PhoneNo, DepartmentID, DepartmentName, Designation FROM staff_user WHERE UserName = ?";
$stmt = $conn->prepare($query);
if (!$stmt) {
    die("Query preparation failed: " . $conn->error);
}
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

// Check if staff data exists
if ($result->num_rows === 0) {
    die("Error: No staff record found for '$username'");
}

// Fetch data
$staff = $result->fetch_assoc();

// Close statement and connection
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
/* Sidebar */
.sidebar {
    position: fixed;
    left: 0;
    top: 0;
    width: 250px;
    height: 100%;
    background-color: #343a40;
    color: #fff;
    padding-top: 20px;
    transition: all 0.3s ease;
}

.sidebar button{
    font-size: 30px;
    color: white;
    background: none;
    border: none;
    cursor: pointer;
}

.sidebar ul {
    list-style-type: none;
    padding: 0;
}

.sidebar ul li a {
    display: block;
    color: white;
    text-decoration: none;
    padding: 15px 20px;
    font-size: 18px;
}

.sidebar ul li a:hover {
    background-color: #495057;
}

.toggle-btn {
    font-size: 24px;
    color: white;
    background: none;
    border: none;
    cursor: pointer;
    margin-left: 15px;
}

        .content {
            margin-left: 270px;
            padding: 20px;
        }
        h1 {
            text-align: center;
            margin-top: 20px;
            color: #2c3e50;
        }
        table {
            width: 60%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        table th, table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        table th {
            background-color: #4ca1af;
            color: white;
        }
        table td {
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>


<!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <button class="toggle-btn" onclick="toggleSidebar()">☰</button>
        <ul>
            <li><a href="staff_dashboard.php">Dashboard</a></li>
            <li><a href="create_student.html">Create Student</a></li>
            <li><a href="personal_info.php">Add Student</a></li>
            <li><a href="view_student_details.php">View Student Details</a></li>
            <li><a href="mark_attendance.php">Mark Attendance</a></li>
        </ul>
    </div>

<div class="content">
    <h1>Staff Profile</h1>

    <?php if ($staff): ?>
        <table>
            <tr><th>ID</th><td><?= htmlspecialchars($staff['ID']); ?></td></tr>
            <tr><th>Name</th><td><?= htmlspecialchars($staff['StaffName']); ?></td></tr>
            <tr><th>Username</th><td><?= htmlspecialchars($staff['UserName']); ?></td></tr>
            <tr><th>Email</th><td><?= htmlspecialchars($staff['Email']); ?></td></tr>
            <tr><th>Phone No.</th><td><?= htmlspecialchars($staff['PhoneNo']); ?></td></tr>
            <tr><th>Department ID</th><td><?= htmlspecialchars($staff['DepartmentID']); ?></td></tr>
            <tr><th>Department Name</th><td><?= htmlspecialchars($staff['DepartmentName']); ?></td></tr>
            <tr><th>Designation</th><td><?= htmlspecialchars($staff['Designation']); ?></td></tr>
        </table>
    <?php else: ?>
        <p style="text-align: center; color: red;">No staff details found.</p>
    <?php endif; ?>
</div>

</body>
</html>

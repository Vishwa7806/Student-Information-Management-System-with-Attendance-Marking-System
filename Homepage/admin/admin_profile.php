<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "attendance";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the user is logged in
if (!isset($_SESSION['admin_username'])) {
    header("Location: admin_login.html"); // Redirect to login page if not logged in
    exit();
}

// Get the logged-in admin's username
$admin_username = $_SESSION['admin_username'];

// Query to get the admin's details from the database
$query = "SELECT ID, Name, `UserName`, Email, Password FROM admin_user WHERE `UserName` = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $admin_username); // Bind the username parameter
$stmt->execute(); // Execute the query
$result = $stmt->get_result(); // Get the result

// Fetch the admin details
$admin = $result->fetch_assoc();

if (!$admin) {
    echo "Admin details not found.";
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile</title>
    <style>
        /* Styling for the profile page */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }


/* Sidebar styling */
.sidebar {
    width: 250px;
    height: 100vh;
    background: linear-gradient(to bottom, #2c3e50, #4ca1af);    color: white;
    position: fixed;
    padding: 30px 20px;
    top: 0;
    left: 0;
    margin: 0;
    font-family: 'Arial', sans-serif;
    box-shadow: 3px 0 15px rgba(0, 0, 0, 0.2);
}

.sidebar ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.sidebar ul li {
    margin: 10px 0;
}

.sidebar ul li a {
    color: white;
    text-decoration: none;
    font-size: 20px;
    display: block;
    padding: 12px;
    border-radius: 8px;
    transition: all 0.3s ease;
    font-weight: bold;
}

.sidebar ul li a:hover {
    background: #1abc9c;
    transform: translateX(10px);
}


        h1 {
            text-align: center;
            margin-top: 20px;
        }
        table {
            width: 50%;
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
    <h2 style= "color:white; text-align:left;">Menu</h2>
        <ul>
        <li><a href="admin_dashboard.php">Dashboard</a></li>
            <li><a href="staff_details.php">Staff Details</a></li>
            <li><a href="student_details.php">Student Details</a></li>
            <li><a href="view_attendance.php">Attendance Details</a></li>
        </ul>
    </div>


    <h1>Admin Profile</h1>
    
    <!-- Admin details in a table -->
    <table>
        <tr>
            <th>ID</th>
            <td><?php echo htmlspecialchars($admin['ID']); ?></td>
        </tr>
        <tr>
            <th>Name</th>
            <td><?php echo htmlspecialchars($admin['Name']); ?></td>
        </tr>
        <tr>
            <th>Username</th>
            <td><?php echo htmlspecialchars($admin['UserName']); ?></td>
        </tr>
        <tr>
            <th>Email</th>
            <td><?php echo htmlspecialchars($admin['Email']); ?></td>
        </tr>
        <tr>
            <th>Password</th>
            <td><?php echo htmlspecialchars($admin['Password']); ?></td>
        </tr>
    </table>

    

</body>
</html>

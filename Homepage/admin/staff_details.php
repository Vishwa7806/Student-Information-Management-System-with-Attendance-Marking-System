<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "attendance";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Delete functionality
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete"])) {
    $id = $_POST["ID"];

    // Delete query
    $sql = "DELETE FROM staff_user WHERE ID='$id'";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Staff deleted successfully!'); window.location.href='staff_details.php';</script>";
    } else {
        echo "<script>alert('Error deleting staff: " . $conn->error . "');</script>";
    }
}

// Fetch all staff data
$query = "SELECT ID, StaffName, UserName, Email, PhoneNo, DepartmentId, DepartmentName, Designation FROM staff_user";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Details</title>
    <link rel="stylesheet" href="../css/staff_details.css">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
          <h2 style= "color:white; text-align:left;">Menu</h2>
        <ul>
            <li><a href="admin_dashboard.php">Dashboard</a></li>
            <li><a href="staff_details.php">Staff Details</a></li>
            <li><a href="student_details.php">Student Details</a></li>
            <li><a href="view_attendance.php">Attendance Details</a></li>
        </ul>
    </div>

    <!-- Main Content -->
     
    <div class="main-content">
        <header>
            <h1>Staff Details</h1>
        </header>

        <div class="content">
            <!-- Add Staff Button -->
            <div class="add-staff-button-container">
                <a href="add_staff.html" class="add-staff-button">Add Staff</a>
            </div>

            <!-- Staff Details Table -->
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Staff Name</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Phone No.</th>
                        <th>Department ID</th>
                        <th>Department Name</th>
                        <th>Designation</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $staff_id = $row['ID'];
                            $staff_name = $row['StaffName'];
                            $username = $row['UserName'];
                            $email = $row['Email'];
                            $phone_no = $row['PhoneNo'];
                            $department_id = $row['DepartmentId'];
                            $department_name = $row['DepartmentName'];
                            $designation = $row['Designation'];
                            ?>
                            <tr>
                                <td><?php echo $staff_id; ?></td>
                                <td><?php echo $staff_name; ?></td>
                                <td><?php echo $username; ?></td>
                                <td><?php echo $email; ?></td>
                                <td><?php echo $phone_no; ?></td>
                                <td><?php echo $department_id; ?></td>
                                <td><?php echo $department_name; ?></td>
                                <td><?php echo $designation; ?></td>
                                <td>
                                    <!-- Edit Button -->
                                    <a href="edit_staff.php?ID=<?php echo $staff_id; ?>" class="edit-button">Edit</a>

                                    <!-- Delete Button -->
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="ID" value="<?php echo $staff_id; ?>">
                                        <button type="submit" name="delete" onclick="return confirm('Are you sure you want to delete this staff?');" class="delete-button">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        echo "<tr><td colspan='9'>No staff found!</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="staff_details.js"></script>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>

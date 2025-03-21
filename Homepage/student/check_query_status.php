<?php
session_start();

// Database connection details
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

// Fetch student's details from session
$student_name = $_SESSION['student_name'];
$register_no = $_SESSION['register_no'];

// Fetch raised queries for the student based on register number
$query = "SELECT * FROM queries WHERE register_no = '$register_no' ORDER BY query_raised_time DESC";
$result = $conn->query($query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check Query Status</title>
    <link rel="stylesheet" href="../css/check_query_status.css">
</head>
<body>


    <div class="sidebar">
        <h2 style="color:white; text-align:left;">Menu</h2>
        <ul>
            <li><a href="student_dashboard.php">Dashboard</a></li>
            <li><a href="queries.php">Query</a></li>
            <li><a href="check_query_status.php">Query Status</a></li>
            <li><a href="settings.php">Settings</a></li>
            <li><a href="student_logout.php">Logout</a></li>
        </ul>
    </div>



    <div class="container">
        <h2>Check Your Query Status</h2>

        <?php if ($result->num_rows > 0): ?>
            <!-- Display raised queries -->
            <table>
                <thead>
                    <tr>
                        <th>Query ID</th>
                        <th>Subject</th>
                        <th>Query Description</th>
                        <th>Query Updated At</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($query_row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $query_row['query_id']; ?></td>
                            <td><?php echo $query_row['subject']; ?></td>
                            <td><?php echo $query_row['query_description']; ?></td>
                            <td><?php echo $query_row['action_taken_time']; ?></td>
                            <td class="status <?php echo strtolower(str_replace(' ', '-', $query_row['status'])); ?>">
                                <?php echo $query_row['status']; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>You have raised no queries yet.</p>
        <?php endif; ?>

    </div>
</body>
</html>

<?php
$conn->close();
?>

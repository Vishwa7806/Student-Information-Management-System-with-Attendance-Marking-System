<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "attendance";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch Batches for the dropdown
function getBatches() {
    global $conn;
    $result = $conn->query("SELECT DISTINCT Batch FROM personal_info");
    $batches = [];
    while ($row = $result->fetch_assoc()) {
        $batches[] = $row['Batch'];
    }
    return $batches;
}

// Handle form submission (fetch attendance data)
$attendanceData = [];
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['batch']) && isset($_POST['date'])) {
    $batch = $_POST['batch'];
    $date = $_POST['date'];

    // SQL Query to get attendance data for the selected batch and date
    $sql = "
        SELECT 
            RegisterNo, 
            Name,
            MAX(CASE WHEN Hour = '1st Hour' THEN Status ELSE NULL END) AS `1st Hour`,
            MAX(CASE WHEN Hour = '2nd Hour' THEN Status ELSE NULL END) AS `2nd Hour`,
            MAX(CASE WHEN Hour = '3rd Hour' THEN Status ELSE NULL END) AS `3rd Hour`,
            MAX(CASE WHEN Hour = '4th Hour' THEN Status ELSE NULL END) AS `4th Hour`,
            MAX(CASE WHEN Hour = '5th Hour' THEN Status ELSE NULL END) AS `5th Hour`
        FROM 
            attendance_record
        WHERE 
            Batch = ? AND Date = ?
        GROUP BY 
            RegisterNo, Name
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $batch, $date);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch the data into an array
    while ($row = $result->fetch_assoc()) {
        $attendanceData[] = $row;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Attendance View</title>
    <link rel="stylesheet" href="../css/view_attendance.css"> <!-- Include your external CSS file -->
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

<!-- Admin Attendance Form -->
<div class="container">
    <h2>View Attendance</h2>

    <!-- Attendance Filters -->
    <form action="view_attendance.php" method="POST">
        <label for="batch">Select Batch:</label>
        <select name="batch" id="batch" required>
            <option value="">Select Batch</option>
            <?php
            $batches = getBatches();
            foreach ($batches as $batch) {
                echo "<option value='$batch'>$batch</option>";
            }
            ?>
        </select>

        <label for="date">Select Date:</label>
        <input type="date" id="date" name="date" required>

        <button type="submit">View Attendance</button>
    </form>

    <?php if (!empty($attendanceData)): ?>
        <!-- Attendance Table -->
        <table>
            <thead>
                <tr>
                    <th>Register No</th>
                    <th>Name</th>
                    <th>1st Hour</th>
                    <th>2nd Hour</th>
                    <th>3rd Hour</th>
                    <th>4th Hour</th>
                    <th>5th Hour</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($attendanceData as $data): ?>
                    <tr>
                        <td><?php echo $data['RegisterNo']; ?></td>
                        <td><?php echo $data['Name']; ?></td>
                        <td><?php echo $data['1st Hour'] ?? '-'; ?></td>
                        <td><?php echo $data['2nd Hour'] ?? '-'; ?></td>
                        <td><?php echo $data['3rd Hour'] ?? '-'; ?></td>
                        <td><?php echo $data['4th Hour'] ?? '-'; ?></td>
                        <td><?php echo $data['5th Hour'] ?? '-'; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

</div>

<script>
    // Sidebar toggle functionality (if needed)
    function toggleSidebar() {
        document.getElementById("sidebar").classList.toggle("active");
    }
</script>

</body>
</html>

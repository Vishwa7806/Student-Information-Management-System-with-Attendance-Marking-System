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


// Fetch Total Staff Count
$staffQuery = "SELECT COUNT(*) AS total_staff FROM staff_user";
$staffResult = mysqli_query($conn, $staffQuery);
$staffRow = mysqli_fetch_assoc($staffResult);
$total_staff = $staffRow['total_staff'];

// Fetch Total Student Count
$total_students_query = "SELECT COUNT(*) as total_students FROM student_user";
$total_students_result = $conn->query($total_students_query);
$total_students = $total_students_result->fetch_assoc()['total_students'];

// Fetch Verified Student Count
$verified_students_query = "SELECT COUNT(*) as verified_students FROM student_user WHERE Status = 'Verified'";
$verified_students_result = $conn->query($verified_students_query);
$verified_students = $verified_students_result->fetch_assoc()['verified_students'];

// Fetch Recent Queries
$queries_query = "SELECT * FROM queries ORDER BY action_taken_time DESC";
$queries_result = $conn->query($queries_query);

if (!isset($_SESSION['admin_name'])) {
    echo 'Session not set';
} else {
    // Proceed to display the welcome message with the admin name
    $admin_name = $_SESSION['admin_name'];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../css/admin_dashboard.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
            <div class="header-left">
                 <h1> Welcome, <?php echo htmlspecialchars($_SESSION['admin_name']); ?> 😊 </h1>
            </div>
            <div class="header-right">
                <div class="admin-icon" onclick="toggleDropdown()">
                    <img src="../images/admin_icon.jpg" alt="Admin" class="admin-avatar">
                    <div class="dropdown-menu" id="dropdownMenu">
                        <a href="admin_profile.php">Profile</a>
                        <a href="admin_logout.php">Logout</a>
                    </div>
                </div>
            </div>
        </header>

        <div class="content">
            <div class="row">
                <div class="card">
                    <h3>Total Staffs</h3>
                    <p><?php echo $total_staff; ?></p>
                </div>
            </div>

            <div class="row">
                <div class="column">
                    <h3>Total Students Data</h3>
                    <p><?php echo $total_students; ?></p>
                </div>
                <div class="column">
                    <h3>Verified Student Data</h3>
                    <p><?php echo $verified_students; ?></p>
                </div>
            </div>

            <!-- Chart Section -->
            <div class="chart-section">
                <canvas id="studentDataChart"></canvas>
            </div>

            <!-- Queries Section -->
            <div class="queries-section">
                <h3>Recent Queries</h3>
                <table>
                    <thead>
                        <tr>
                <th>Query ID</th>
                <th>RegisterNo</th>
                <th>Student Name</th>
                <th>Subject</th>
                <th>Query Description</th>
                <th>Query Raised At</th>
                <th>Status</th>
                <th>Uploaded File</th> <!-- New column for file -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($queries_result->num_rows > 0) { ?>
                            <?php while ($query = $queries_result->fetch_assoc()) { ?>
                                <tr>
                                    <td><?php echo $query['query_id']; ?></td>
                                    <td><?php echo $query['register_no']; ?></td>
                                    <td><?php echo $query['student_name']; ?></td>
                                    <td><?php echo $query['subject']; ?></td>
                                    <td><?php echo $query['query_description']; ?></td>
                                    <td><?php echo $query['query_raised_time']; ?></td>
                                    <td class="status <?php echo strtolower(str_replace(' ', '-', $query['status'])); ?>">
    <?php echo $query['status']; ?>
</td>

                                    <td>
                                        <?php if (!empty($query['file_path'])) { ?>
                                <a href="/Homepage/<?php echo $query['file_path']; ?>" target="_blank">View File</a>
                            <?php } else { ?>
                                No File
                            <?php } ?>
                        </td>
                                </tr>
                            <?php } ?>
                        <?php } else { ?>
                            <tr><td colspan="7">No queries found.</td></tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
    function toggleDropdown() {
        var dropdown = document.getElementById("dropdownMenu");
        dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
    }

    // Hide dropdown if user clicks outside
    window.onclick = function(event) {
        if (!event.target.closest('.admin-icon')) {
            document.getElementById("dropdownMenu").style.display = "none";
        }
    };

const ctx = document.getElementById('studentDataChart').getContext('2d');
const studentDataChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Verified', 'Not Verified'],
        datasets: [{
            label: 'Student Verification Status',
            data: [<?php echo $verified_students; ?>, <?php echo $total_students - $verified_students; ?>],
            backgroundColor: ['#33FF57', '#FF5733'], // Example colors
            borderColor: ['#33FF57', '#FF5733'],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        aspectRatio: 1,
        animation: {
            duration: 1500,  // Duration of the animation
            easing: 'easeOutBounce',  // Bounce animation
            onProgress: function(animation) {
                // Optional: Can add custom progress logic if needed
            },
        },
        scales: {
            x: {
                beginAtZero: true
            },
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>
</body>
</html>

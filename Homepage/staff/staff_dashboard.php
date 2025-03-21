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

// Fetch staff name from session
$staff_name = isset($_SESSION['staff_name']) ? $_SESSION['staff_name'] : 'Staff';

// Get total students
$total_students_query = "SELECT COUNT(*) as total_students FROM student_user";
$total_students_result = $conn->query($total_students_query);
$total_students = $total_students_result->fetch_assoc()['total_students'];

// Get verified students
$verified_students_query = "SELECT COUNT(*) as verified_students FROM student_user WHERE Status = 'Verified'";
$verified_students_result = $conn->query($verified_students_query);
$verified_students = $verified_students_result->fetch_assoc()['verified_students'];

// Get queries
$queries_query = "SELECT * FROM queries ORDER BY action_taken_time DESC"; // Updated field name here
$queries_result = $conn->query($queries_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard</title>
    <link rel="stylesheet" href="../css/staff_dashboard.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
       <h2 style="color:white; text-align:left;">Menu</h2>
        <ul>
            <li><a href="staff_dashboard.php">Dashboard</a></li>
            <li><a href="create_student.html">Create Student</a></li>
            <li><a href="personal_info.php">Add Student</a></li>
            <li><a href="view_student_details.php">View Student Details</a></li>
            <li><a href="mark_attendance.php">Mark Attendance</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <header>
            <div class="header-left">
                <h1>Welcome, <?php echo htmlspecialchars($staff_name); ?>😊</h1>
            </div>
            <div class="header-right">
                <div class="admin-icon" onclick="toggleDropdown()">
                    <img src="../images/admin_icon.jpg" alt="Staff" class="admin-avatar">
                    <div class="dropdown-menu" id="dropdownMenu">
                        <a href="staff_profile.php">Profile</a>
                        <a href="staff_logout.php">Logout</a>
                    </div>
                </div>
            </div>
        </header>

        <div class="content">
            <div class="row">
                <!-- Total Students Card -->
                <div class="column">
                    <h3>Total Students</h3>
                    <p><?php echo $total_students; ?></p>
                </div>
                <!-- Verified Students Card -->
                <div class="column">
                    <h3>Verified Students</h3>
                    <p><?php echo $verified_students; ?></p>
                </div>
            </div>

            <!-- Pie Chart Section -->
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
                <th>Uploaded File</th> <!-- New column for file -->
                <th>Action</th>
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
                        <td>
                            <?php if (!empty($query['file_path'])) { ?>
                                <a href="/Homepage/<?php echo $query['file_path']; ?>" target="_blank">View File</a>
                            <?php } else { ?>
                                No File
                            <?php } ?>
                        </td>
             
<td>
    <form method="POST" action="update_query_status.php">
        <input type="hidden" name="query_id" value="<?php echo $query['query_id']; ?>">
        <select name="status" class="status-dropdown" onchange="updateDropdownColor(this); this.form.submit()" 
        <?php if ($query['status'] == 'Resolved') echo 'disabled'; ?>>
            <option value="Pending" <?php echo ($query['status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
            <option value="In Progress" <?php echo ($query['status'] == 'In Progress') ? 'selected' : ''; ?>>In Progress</option>
            <option value="Resolved" <?php echo ($query['status'] == 'Resolved') ? 'selected' : ''; ?>>Resolved</option>
        </select>
    </form>
</td>

                   </tr>
                <?php } ?>
            <?php } else { ?>
                <tr><td colspan="9">No queries found.</td></tr>
            <?php } ?>
        </tbody>
    </table>
</div>

</div>
</div>

    <!-- Script to generate the Pie Chart -->
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
            type: 'pie',
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
                aspectRatio: 1
            }
        });

function updateStatus(query_id) {
    var status = document.getElementById('status-dropdown-' + query_id).value;
    var formData = new FormData();
    formData.append('query_id', query_id);
    formData.append('status', status);

    // Send AJAX request to update the status
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'update_query_status.php', true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            if (xhr.responseText === "success") {
                console.log('Query status updated successfully!');
                if (status === 'Resolved') {
                    // Disable the dropdown once the status is set to Resolved
                    var dropdown = document.getElementById('status-dropdown-' + query_id);
                    dropdown.disabled = true;
                    dropdown.style.pointerEvents = 'none';
                    dropdown.style.opacity = '0.5';
                }
                // Use the default browser alert
                if (status === 'In Progress') {
                    alert('The query is under progress.');
                } else if (status === 'Resolved') {
                    alert('The query has been cleared.');
                }
            } else {
                console.log('Error updating status');
                alert('Error updating status');
            }
        } else {
            console.log('Request failed. Returned status of ' + xhr.status);
            alert('Request failed');
        }
    };
    xhr.send(formData);
}

 // Function to update the dropdown's background color based on selected status
   function updateDropdownColor(selectElement) {
    const status = selectElement.value;

    // Remove existing status color classes
    selectElement.classList.remove('pending', 'in-progress', 'resolved', 'not-processed');

    // Add the new status color class
    if (status === 'Pending') {
        selectElement.classList.add('pending');
    } else if (status === 'In Progress') {
        selectElement.classList.add('in-progress');
    } else if (status === 'Resolved') {
        selectElement.classList.add('resolved');
    } else if (status === 'Not Processed Yet') {
        selectElement.classList.add('not-processed');
    }
}

// Apply initial color on page load for all existing dropdowns
document.addEventListener('DOMContentLoaded', function() {
    const statusDropdowns = document.querySelectorAll('.status-dropdown');
    statusDropdowns.forEach(function(dropdown) {
        updateDropdownColor(dropdown); // Apply color based on selected value
    });
});


  </script>
</body>
</html>
<?php
session_start(); // Start the session

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Store project info in session
    $_SESSION['projectTitle'] = $_POST['projectTitle'];
    $_SESSION['guideName'] = $_POST['guideName'];
    $_SESSION['projectDescription'] = $_POST['projectDescription'];

    // Redirect to the preview page
    header("Location: preview.php");
    exit();
}

// Retrieve 'regno' from session (if available)
$regno = isset($_SESSION['regno']) ? $_SESSION['regno'] : '';  // Default to empty if not set
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Project Information</title>
    <link rel="stylesheet" href="../css/project_info.css">
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
    <div class="container">
        <h1>Student Project Information</h1>
        <form id="projectForm" method="POST" action="project_info.php">
            <div class="form-group">
                <label for="regno">Register Number</label>
                <input type="text" id="regno" name="regno" value="<?php echo $regno; ?>" readonly>
            </div>

            <div class="form-group">
                <label for="projectTitle">Project Title</label>
                <input type="text" id="projectTitle" name="projectTitle" required>
            </div>

            <div class="form-group">
                <label for="guideName">Project Guide Name</label>
                <input type="text" id="guideName" name="guideName" required>
            </div>

            <div class="form-group">
                <label for="projectDescription">Project Description</label>
                <textarea id="projectDescription" name="projectDescription" rows="3" required></textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn save-next">Save & Finish</button>
                <button type="reset" class="btn clear">Clear</button>
            </div>
        </form>
    </div>

    <script>
        // Retrieve and Set Register Number on Page Load
        document.addEventListener("DOMContentLoaded", function() {
            var regno = sessionStorage.getItem("regno");
            if (regno) {
                document.getElementById("regno").value = regno;
            }
        });

        // Prevent Register Number from Clearing on Reset
        document.getElementById("projectForm").addEventListener("reset", function() {
            setTimeout(function() {
                var regno = sessionStorage.getItem("regno");
                if (regno) {
                    document.getElementById("regno").value = regno;
                }
            }, 10); // Small delay to ensure value is restored after reset
        });
    </script>

</body>
</html>

<?php
session_start(); // Start the session

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Store the form data in session
    $_SESSION['sem1'] = $_POST['sem1'];
    $_SESSION['sem2'] = $_POST['sem2'];
    $_SESSION['sem3'] = $_POST['sem3'];
    $_SESSION['sem4'] = $_POST['sem4'];

    // Redirect to the next page (project_info.html)
    header("Location: project_info.php");
    exit();
}

// Retrieve the 'regno' from sessionStorage (if available)
$regno = isset($_SESSION['regno']) ? $_SESSION['regno'] : '';  // Default to an empty string if not set
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Academic Information</title>
    <link rel="stylesheet" href="../css/academic_info.css">
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
        <h1>Student Academic Information</h1>
        <form id="academicForm" method="POST" action="academic_info.php">
            <div class="form-group">
                <label for="regno">Register Number</label>
                <input type="text" id="regno" name="regno" value="<?php echo $regno; ?>" readonly>
            </div>
            
            <div class="form-group">
                <label for="sem1">Semester 1 Marks</label>
                <input type="number" id="sem1" name="sem1" >
            </div>

            <div class="form-group">
                <label for="sem2">Semester 2 Marks</label>
                <input type="number" id="sem2" name="sem2" >
            </div>

            <div class="form-group">
                <label for="sem3">Semester 3 Marks</label>
                <input type="number" id="sem3" name="sem3" >
            </div>

            <div class="form-group">
                <label for="sem4">Semester 4 Marks</label>
                <input type="number" id="sem4" name="sem4" >
            </div>

            <div class="form-actions">
                <button type="submit" class="btn save-next">Save & Next</button>
                <button type="reset" class="btn clear">Clear</button>
            </div>
        </form>
    </div>

    <script>
        // Retrieve and Set Register Number on Page Load (if available in sessionStorage)
        document.addEventListener("DOMContentLoaded", function() {
            var regno = sessionStorage.getItem("regno");
            if (regno) {
                document.getElementById("regno").value = regno;
            }
        });

        // Prevent Register Number from Clearing on Reset
        document.getElementById("academicForm").addEventListener("reset", function() {
            setTimeout(function() {
                var regno = sessionStorage.getItem("regno");
                if (regno) {
                    document.getElementById("regno").value = regno;
                }
            }, 10); // Delay to ensure value is restored after reset
        });
    </script>

</body>
</html>

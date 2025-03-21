<?php
session_start(); // Start the session

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Store the form data in session
    $_SESSION['bankName'] = $_POST['bankName'];
    $_SESSION['accountNo'] = $_POST['accountNo'];
    $_SESSION['ifscCode'] = $_POST['ifscCode'];
    $_SESSION['bankAddress'] = $_POST['bankAddress'];

    // Redirect to the next page (academic_info.php)
    header("Location: academic_info.php");
    exit();
}

// Retrieve the 'regno' and other values from session (if available)
$regno = isset($_SESSION['regno']) ? $_SESSION['regno'] : '';  // Default to an empty string if not set
$ifscCode = isset($_SESSION['ifscCode']) ? $_SESSION['ifscCode'] : 'Not Provided'; // Default to 'Not Provided' if not set
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Bank Information</title>
    <link rel="stylesheet" href="../css/bank_info.css">
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
        <h1>Student Bank Information</h1>
        <form id="bankForm" method="POST" action="bank_info.php">
            <div class="form-group">
                <label for="regno">Register Number</label>
                <input type="text" id="regno" name="regno" value="<?php echo $regno; ?>" readonly>
            </div>

            <div class="form-group">
                <label for="bankName">Bank Name</label>
                <input type="text" id="bankName" name="bankName" required>
            </div>

            <div class="form-group">
                <label for="accountNo">Account Number</label>
                <input type="text" id="accountNo" name="accountNo" required>
            </div>

            <div class="form-group">
                <label for="ifscCode">IFSC Code</label>
                <input type="text" id="ifscCode" name="ifscCode" value="<?php echo isset($_SESSION['ifscCode']) ? $_SESSION['ifscCode'] : ''; ?>" required>
            </div>

            <div class="form-group">
                <label for="bankAddress">Bank Address</label>
                <textarea id="bankAddress" name="bankAddress" rows="2" required><?php echo isset($_SESSION['bankAddress']) ? $_SESSION['bankAddress'] : ''; ?></textarea>
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
        document.getElementById("bankForm").addEventListener("reset", function() {
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

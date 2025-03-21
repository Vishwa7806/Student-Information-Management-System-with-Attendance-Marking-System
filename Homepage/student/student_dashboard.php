<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "attendance";

$conn = mysqli_connect($servername, $username, $password, $database);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch Register No using the username stored in session
$register_no = $_SESSION['register_no'];  // Get the Register No from session

// Initialize student details arrays
$student_details = [];
$bank_details = [];
$academic_details = [];
$attendance_details = [];
$present_hours = 0;
$total_hours_marked = 0;

// Fetch personal details using Register No
if (!empty($register_no)) {
    $query = "SELECT * FROM personal_info WHERE RegisterNo = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $register_no);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $student_details = $row;  // Store the personal details
    }
    $stmt->close();

    // Fetch bank details using Register No
    $query = "SELECT * FROM bank_info WHERE RegisterNo = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $register_no);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $bank_details = $row;  // Store the bank details
    }
    $stmt->close();

    // Fetch academic details using Register No
    $query = "SELECT * FROM academic_info WHERE RegisterNo = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $register_no);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $academic_details = $row;  // Store the academic details
    }
    $stmt->close();

    // Fetch attendance details (All records for the student)
    $query = "SELECT * FROM attendance_record WHERE RegisterNo = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $register_no);
    $stmt->execute();
    $result = $stmt->get_result();

    // Count the "Present" status for each hour and the total attendance records
    while ($row = $result->fetch_assoc()) {
        if ($row['Status'] == 'Present') {
            $present_hours++;  // Count the number of hours the student was present
        }
        $total_hours_marked++;  // Count the total number of attendance records (both present and absent)
    }

    // Calculate attendance percentage
    if ($total_hours_marked > 0) {
    $attendance_percentage = ($present_hours / $total_hours_marked) * 100;
} else {
    $attendance_percentage = 0; // Avoid division by zero
}

    // Store attendance details for later display
    $attendance_details = [
        'present_hours' => $present_hours,
        'total_hours_marked' => $total_hours_marked,
        'attendance_percentage' => round($attendance_percentage, 2),
    ];

   $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="../css/student_dashboard.css">
    <script>
        // Display personal details when the button is clicked
        function displayPersonalDetails() {
            var studentDetails = <?php echo json_encode($student_details); ?>;
            var htmlContent = "<div class='details-header'><h2>Personal Details</h2></div>";
            htmlContent += "<div class='details-content'>";
            htmlContent += "<div><label>Register Number</label><input type='text' value='" + studentDetails.RegisterNo + "' readonly></div>";
            htmlContent += "<div><label>Name</label><input type='text' value='" + studentDetails.Name + "' readonly></div>";
            htmlContent += "<div><label>Mobile No</label><input type='text' value='" + studentDetails.MobileNo + "' readonly></div>";
            htmlContent += "<div><label>Address</label><input type='text' value='" + studentDetails.Address + "' readonly></div>";
            htmlContent += "<div><label>Community</label><input type='text' value='" + studentDetails.Community + "' readonly></div>";
            htmlContent += "<div><label>Community Name</label><input type='text' value='" + studentDetails.CommunityName + "' readonly></div>";
            htmlContent += "<div><label>Date of Birth</label><input type='text' value='" + studentDetails.DateOfBirth + "' readonly></div>";
            htmlContent += "<div><label>Gender</label><input type='text' value='" + studentDetails.Gender + "' readonly></div>";
            htmlContent += "<div><label>Email</label><input type='text' value='" + studentDetails.Email + "' readonly></div>";
            htmlContent += "<div><label>Religion</label><input type='text' value='" + studentDetails.Religion + "' readonly></div>";
            htmlContent += "<div><label>Aadhar Number</label><input type='text' value='" + studentDetails.AadharNo + "' readonly></div>";
            htmlContent += "<div><label>Batch</label><input type='text' value='" + studentDetails.Batch + "' readonly></div>";
            htmlContent += "<div><label>Father's Name</label><input type='text' value='" + studentDetails.FatherName + "' readonly></div>";
            htmlContent += "<div><label>Annual Income</label><input type='text' value='" + studentDetails.AnnualIncome + "' readonly></div>";
            htmlContent += "<div><label>Disability</label><input type='text' value='" + studentDetails.Disability + "' readonly></div>";
            htmlContent += "</div>";
            document.getElementById("details-container").innerHTML = htmlContent;
        }

        // Display bank details when the button is clicked
        function displayBankDetails() {
            var bankDetails = <?php echo json_encode($bank_details); ?>;
            var htmlContent = "<div class='details-header'><h2>Bank Details</h2></div>";
            htmlContent += "<div class='details-content'>";
            htmlContent += "<div><label>Bank Name</label><input type='text' value='" + bankDetails.BankName + "' readonly></div>";
            htmlContent += "<div><label>Account Number</label><input type='text' value='" + bankDetails.AccountNumber + "' readonly></div>";
            htmlContent += "<div><label>IFSC Code</label><input type='text' value='" + bankDetails.IFSCCODE + "' readonly></div>";
            htmlContent += "<div><label>Bank Address</label><input type='text' value='" + bankDetails.BankBranch + "' readonly></div>";
            htmlContent += "</div>";
            document.getElementById("details-container").innerHTML = htmlContent;
        }

        // Display academic details when the button is clicked
        function displayAcademicDetails() {
            var academicDetails = <?php echo json_encode($academic_details); ?>;
            var htmlContent = "<div class='details-header'><h2>Academic Details</h2></div>";
            htmlContent += "<div class='details-content'>";
            htmlContent += "<div><label>Semester 1 Marks</label><input type='text' value='" + academicDetails.Semester1 + "' readonly></div>";
            htmlContent += "<div><label>Semester 2 Marks</label><input type='text' value='" + academicDetails.Semester2 + "' readonly></div>";
            htmlContent += "<div><label>Semester 3 Marks</label><input type='text' value='" + academicDetails.Semester3 + "' readonly></div>";
            htmlContent += "<div><label>Semester 4 Marks</label><input type='text' value='" + academicDetails.Semester4 + "' readonly></div>";
            htmlContent += "</div>";
            document.getElementById("details-container").innerHTML = htmlContent;
        }

        // Display attendance details when the button is clicked
        function displayAttendanceDetails() {
            var attendanceDetails = <?php echo json_encode($attendance_details); ?>;
            var htmlContent = "<div class='details-header'><h2>Attendance Details</h2></div>";
            htmlContent += "<div class='details-content'>";
            htmlContent += "<div><label>Total Hours Marked</label><input type='text' value='" + attendanceDetails.total_hours_marked + "' readonly></div>";
            htmlContent += "<div><label>Present Hours</label><input type='text' value='" + attendanceDetails.present_hours + "' readonly></div>";
            htmlContent += "<div><label>Attendance Percentage</label><input type='text' value='" + attendanceDetails.attendance_percentage + "%' readonly></div>";
            htmlContent += "</div>";
            document.getElementById("details-container").innerHTML = htmlContent;
        }

        // Verify Data button click handler
        function verifyData() {
            var registerNo = <?php echo json_encode($_SESSION['register_no']); ?>; // Get the Register No from the session

            // Get the Verify Data button by its class
            var verifyButton = document.querySelector(".verify-btn");

            // Send the Ajax request to the PHP script
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "verify_data.php", true);  // Make a POST request to verify_data.php
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    // Disable the button after successful verification
                    verifyButton.disabled = true;  // Disable the button

                    // Optionally, change the button text to show verification status
                    verifyButton.textContent = "Verified";  // Update the button text

                    // Show a confirmation message
                    alert(xhr.responseText); // Show response message from PHP
                }
            };
            xhr.send("register_no=" + registerNo); // Send the Register No to the server
        }

        // Function to check if the data is verified and disable the button
window.onload = function() {
    // Check if the student's data is verified and adjust button accordingly
    var dataVerified = <?php echo json_encode($_SESSION['data_verified'] ?? false); ?>;
    
    // If data is verified, disable the button
    if (dataVerified) {
        document.querySelector(".verify-btn").disabled = true;
        document.querySelector(".verify-btn").textContent = "Verified";
    } else {
        // If data is not verified, enable the button
        document.querySelector(".verify-btn").disabled = false;
        document.querySelector(".verify-btn").textContent = "Verify Data";
    }
}


    </script>
</head>
<body>
    <header>
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['student_name']); ?>😊</h1>
    </header>
    
    <div class="sidebar">
        <h2>Menu</h2>
        <ul>
            <li><a href="student_dashboard.php">Dashboard</a></li>
            <li><a href="queries.php">Query</a></li>
            <li><a href="check_query_status.php">Query Status</a></li>
            <li><a href="settings.php">Settings</a></li>
            <li><a href="student_logout.php">Logout</a></li>
        </ul>
    </div>
    
    <div class="main-content">
        <div class="button-container">
            <button class="animated-btn" onclick="displayPersonalDetails()">Personal Details</button>
            <button class="animated-btn" onclick="displayBankDetails()">Bank Details</button>
            <button class="animated-btn" onclick="displayAcademicDetails()">Academic Details</button>
            <button class="animated-btn" onclick="displayAttendanceDetails()">Attendance Details</button>
        </div>

<!-- New line for the Verify Data button, aligned to the right -->

<div class="verify-button-container">
    <button class="verify-btn" onclick="verifyData()">Verify Data</button>
</div> 

               <!-- Here the details will be displayed -->
        <div id="details-container"></div>
    </div>
</body>
</html>       
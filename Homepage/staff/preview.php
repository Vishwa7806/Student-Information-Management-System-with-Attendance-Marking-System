<?php
session_start(); // Start the session

// Check if session data exists, if not, set error flag
$errorFlag = false;
if (!isset($_SESSION['regno'])) {
    $errorFlag = true;
}

$regno = $_SESSION['regno'] ?? null;
$name = $_SESSION['name'] ?? null;
$projectTitle = $_SESSION['projectTitle'] ?? null;
$guideName = $_SESSION['guideName'] ?? null;
$projectDescription = $_SESSION['projectDescription'] ?? null;
$sem1 = $_SESSION['sem1'] ?? null;
$sem2 = $_SESSION['sem2'] ?? null;
$sem3 = $_SESSION['sem3'] ?? null;
$sem4 = $_SESSION['sem4'] ?? null;
$mobile = $_SESSION['mobile'] ?? null;
$address = $_SESSION['address'] ?? null;
$community = $_SESSION['community'] ?? null;
$communityName = $_SESSION['communityName'] ?? null;
$dob = $_SESSION['dob'] ?? null;
$gender = $_SESSION['gender'] ?? null;
$email = $_SESSION['email'] ?? null;
$religion = $_SESSION['religion'] ?? null;
$aadhar = $_SESSION['aadhar'] ?? null;
$batch = $_SESSION['batch'] ?? null;
$fatherName = $_SESSION['fatherName'] ?? null;
$income = $_SESSION['income'] ?? null;
$disability = $_SESSION['disability'] ?? null;
$bankName = $_SESSION['bankName'] ?? null;
$accountNo = $_SESSION['accountNo'] ?? null;
$ifscCode = $_SESSION['ifscCode'] ?? null;
$bankAddress = $_SESSION['bankAddress'] ?? null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview Student Information</title>
    <link rel="stylesheet" href="../css/preview.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
        <h1>Preview Student Information</h1>

        <!-- Personal Info Section -->
        <section id="personalInfoSection">
            <h2>Personal Information</h2>
            <?php if ($regno && $name && $mobile && $address): ?>
                <p><strong>Register Number:</strong> <?php echo htmlspecialchars($regno); ?></p>
                <p><strong>Name:</strong> <?php echo htmlspecialchars($name); ?></p>
                <p><strong>Mobile:</strong> <?php echo htmlspecialchars($mobile); ?></p>
                <p><strong>Address:</strong> <?php echo htmlspecialchars($address); ?></p>
                <p><strong>Community:</strong> <?php echo htmlspecialchars($community);?></p>
                <p><strong>Community Name:</strong> <?php echo htmlspecialchars($communityName);?></p>
                <p><strong>Date of Birth:</strong> <?php echo htmlspecialchars($dob); ?></p>
                <p><strong>Gender:</strong> <?php echo htmlspecialchars($gender); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
                <p><strong>Religion:</strong> <?php echo htmlspecialchars($religion); ?></p>
                <p><strong>Aadhar Number:</strong> <?php echo htmlspecialchars($aadhar); ?></p>
                <p><strong>Batch:</strong> <?php echo htmlspecialchars($batch); ?></p>
                <p><strong>Father's Name:</strong> <?php echo htmlspecialchars($fatherName); ?></p>
                <p><strong>Annual Income:</strong> <?php echo htmlspecialchars($income); ?></p>
                <p><strong>Disability:</strong> <?php echo htmlspecialchars($disability); ?></p>
                <a href="personal_info.php">Edit Personal Info</a>
            <?php else: ?>
                <p>No data available for Personal Information</p>
                <button onclick="showPopup()">No Data Available</button>
            <?php endif; ?>
        </section>

        <!-- Bank Info Section -->
        <section id="bankInfoSection">
            <h2>Bank Information</h2>
            <?php if ($bankName && $accountNo): ?>
                <p><strong>Register Number:</strong> <?php echo htmlspecialchars($regno); ?></p>
                <p><strong>Bank Name:</strong> <?php echo htmlspecialchars($bankName); ?></p>
                <p><strong>Account Number:</strong> <?php echo htmlspecialchars($accountNo); ?></p>
                <p><strong>IFSC Code:</strong> <?php echo htmlspecialchars($ifscCode); ?></p>
                <p><strong>Bank Address:</strong> <?php echo htmlspecialchars($bankAddress); ?></p>
                <a href="bank_info.php">Edit Bank Info</a>
            <?php else: ?>
                <p>No data available for Bank Information</p>
                <button onclick="showPopup()">No Data Available</button>
            <?php endif; ?>
        </section>

        <!-- Academic Info Section -->
        <section id="academicInfoSection">
            <h2>Academic Information</h2>
            <?php if ($sem1 && $sem2 && $sem3 && $sem4): ?>
                <p><strong>Register Number:</strong> <?php echo htmlspecialchars($regno); ?></p>
                <p><strong>Semester 1 Marks:</strong> <?php echo htmlspecialchars($sem1); ?></p>
                <p><strong>Semester 2 Marks:</strong> <?php echo htmlspecialchars($sem2); ?></p>
                <p><strong>Semester 3 Marks:</strong> <?php echo htmlspecialchars($sem3); ?></p>
                <p><strong>Semester 4 Marks:</strong> <?php echo htmlspecialchars($sem4); ?></p>
                <a href="academic_info.php">Edit Academic Info</a>
            <?php else: ?>
                <p>No data available for Academic Information</p>
                <button onclick="showPopup()">No Data Available</button>
            <?php endif; ?>
        </section>

        <!-- Project Info Section -->
        <section id="projectInfoSection">
            <h2>Project Information</h2>
            <?php if ($projectTitle && $guideName && $projectDescription): ?>
                <p><strong>Register Number:</strong> <?php echo htmlspecialchars($regno); ?></p>
                <p><strong>Project Title:</strong> <?php echo htmlspecialchars($projectTitle); ?></p>
                <p><strong>Project Guide Name:</strong> <?php echo htmlspecialchars($guideName); ?></p>
                <p><strong>Project Description:</strong> <?php echo htmlspecialchars($projectDescription); ?></p>
                <a href="project_info.php">Edit Project Info</a>
            <?php else: ?>
                <p>No data available for Project Information</p>
                <button onclick="showPopup()">No Data Available</button>
            <?php endif; ?>
        </section>

        <!-- Submit Data Button -->
        <form action="submit_data.php" method="POST">
            <!-- Hidden Inputs for all session data -->
            <input type="hidden" name="regno" value="<?php echo htmlspecialchars($regno); ?>">
            <input type="hidden" name="name" value="<?php echo htmlspecialchars($name); ?>">
            <input type="hidden" name="mobile" value="<?php echo htmlspecialchars($mobile); ?>">
            <input type="hidden" name="address" value="<?php echo htmlspecialchars($address); ?>">
            <input type="hidden" name="community" value="<?php echo htmlspecialchars($community); ?>">
            <input type="hidden" name="communityName" value="<?php echo htmlspecialchars($communityName); ?>">
            <input type="hidden" name="dob" value="<?php echo htmlspecialchars($dob); ?>">
            <input type="hidden" name="gender" value="<?php echo htmlspecialchars($gender); ?>">
            <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">
            <input type="hidden" name="religion" value="<?php echo htmlspecialchars($religion); ?>">
            <input type="hidden" name="aadhar" value="<?php echo htmlspecialchars($aadhar); ?>">
            <input type="hidden" name="batch" value="<?php echo htmlspecialchars($batch); ?>">
            <input type="hidden" name="fatherName" value="<?php echo htmlspecialchars($fatherName); ?>">
            <input type="hidden" name="income" value="<?php echo htmlspecialchars($income); ?>">
            <input type="hidden" name="disability" value="<?php echo htmlspecialchars($disability); ?>">
            <input type="hidden" name="bankName" value="<?php echo htmlspecialchars($bankName); ?>">
            <input type="hidden" name="accountNo" value="<?php echo htmlspecialchars($accountNo); ?>">
            <input type="hidden" name="ifscCode" value="<?php echo htmlspecialchars($ifscCode); ?>">
            <input type="hidden" name="bankAddress" value="<?php echo htmlspecialchars($bankAddress); ?>">
            <input type="hidden" name="sem1" value="<?php echo htmlspecialchars($sem1); ?>">
            <input type="hidden" name="sem2" value="<?php echo htmlspecialchars($sem2); ?>">
            <input type="hidden" name="sem3" value="<?php echo htmlspecialchars($sem3); ?>">
            <input type="hidden" name="sem4" value="<?php echo htmlspecialchars($sem4); ?>">
            <button type="submit">Submit Data</button>
        </form>
    </div>

    <script>
        function toggleSidebar() {
            document.querySelector(".sidebar").classList.toggle("active");
        }
        function showPopup() {
            alert("No data available for this section.");
        }
    </script>
</body>
</html>

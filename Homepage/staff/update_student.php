<?php
// Database connection
$servername = "localhost";
$username = "root"; // Use your database username
$password = ""; // Use your database password
$dbname = "attendance"; // Use your database name

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_GET['RegNo'])) {
    echo "No student ID provided!";
    exit();
}

$regNo = $_GET['RegNo'];

// Fetch current data for the student (personal info)
$query = "SELECT * FROM personal_info WHERE RegisterNo = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('s', $regNo);
$stmt->execute();
$personalResult = $stmt->get_result();
$personalInfo = $personalResult->fetch_assoc();

// Fetch bank details
$query = "SELECT * FROM bank_info WHERE RegisterNo = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('s', $regNo);
$stmt->execute();
$bankResult = $stmt->get_result();
$bankInfo = $bankResult->fetch_assoc();

// Fetch academic details
$query = "SELECT * FROM academic_info WHERE RegisterNo = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('s', $regNo);
$stmt->execute();
$academicResult = $stmt->get_result();
$academicInfo = $academicResult->fetch_assoc();

// Fetch project details
$query = "SELECT * FROM project_info WHERE RegisterNo = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('s', $regNo);
$stmt->execute();
$projectResult = $stmt->get_result();
$projectInfo = $projectResult->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and sanitize data from the form submission
    $name = $_POST['name'];
    $mobileNo = $_POST['mobileNo'];
    $address = $_POST['address'];
    $community = $_POST['community'];
    $communityName = $_POST['communityName'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $email = $_POST['email'];
    $religion = $_POST['religion'];
    $aadharNo = $_POST['aadharNo'];
    $fathersName = $_POST['FathersName'];
    $annualIncome = $_POST['annualIncome'];
    $disability = $_POST['disability'];

    $bankName = $_POST['bankName'];
    $accountNo = $_POST['accountNo'];
    $ifsccode = $_POST['ifsccode'];
    $bankAddress = $_POST['bankAddress'];

    $sem1 = $_POST['sem1'];
    $sem2 = $_POST['sem2'];
    $sem3 = $_POST['sem3'];
    $sem4 = $_POST['sem4'];

    $projectTitle = $_POST['projectTitle'];
    $projectDescription = $_POST['projectDescription'];

    // Update personal info
    $query = "UPDATE personal_info SET Name = ?, MobileNo = ?, Address = ?, Community = ?, CommunityName = ?, DateofBirth = ?, Gender = ?, Email = ?, Religion = ?, AadharNo = ?, FatherName = ?, AnnualIncome = ?, Disability = ? WHERE RegisterNo = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ssssssssssssss', $name, $mobileNo, $address, $community, $communityName, $dob, $gender, $email, $religion, $aadharNo, $fathersName, $annualIncome, $disability, $regNo);
    $stmt->execute();

    // Update bank details
    $query = "UPDATE bank_info SET BankName = ?, AccountNumber = ?, IFSCCODE = ?, BankBranch = ? WHERE RegisterNo = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('sssss', $bankName, $accountNo, $ifsccode, $bankAddress, $regNo);
    $stmt->execute();

    // Update academic details
    $query = "UPDATE academic_info SET Semester1 = ?, Semester2 = ?, Semester3 = ?, Semester4 = ? WHERE RegisterNo = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('sssss', $sem1, $sem2, $sem3, $sem4, $regNo);
    $stmt->execute();

    // Update project details
    $query = "UPDATE project_info SET ProjectTitle = ?, Description = ? WHERE RegisterNo = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('sss', $projectTitle, $projectDescription, $regNo);
    $stmt->execute();

    // Redirect to a confirmation or success page
    header("Location: update_student.php?RegNo=$regNo&message=Data updated successfully");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Student</title>
    <link rel="stylesheet" href="../css/update_student.css">
</head>
<body>
    <div class="form-container">
        <h2>Update Student Details</h2>

        <!-- Display the success message if it exists -->
        <?php
        if (isset($_GET['message'])) {
            echo "<div class='success-message'>" . htmlspecialchars($_GET['message']) . "</div>";
        }
        ?>

        <form method="POST">
            <h3>Personal Information</h3>
            <label for="name">Name:</label>
            <input type="text" name="name" value="<?php echo $personalInfo['Name']; ?>" required><br>
            
            <label for="mobileNo">Mobile Number:</label>
            <input type="text" name="mobileNo" value="<?php echo $personalInfo['MobileNo']; ?>" required><br>

            <label for="address">Address:</label>
            <input type="text" name="address" value="<?php echo $personalInfo['Address']; ?>" required><br>

            <label for="community">Community:</label>
            <input type="text" name="community" value="<?php echo $personalInfo['Community']; ?>" required><br>

            <label for="communityName">Community Name:</label>
            <input type="text" name="communityName" value="<?php echo $personalInfo['CommunityName']; ?>" required><br>

            <label for="dob">Date of Birth:</label>
            <input type="date" name="dob" value="<?php echo $personalInfo['DateOfBirth']; ?>" required><br>

            <label for="gender">Gender:</label>
            <input type="text" name="gender" value="<?php echo $personalInfo['Gender']; ?>" required><br>

            <label for="email">Email:</label>
            <input type="email" name="email" value="<?php echo $personalInfo['Email']; ?>" required><br>

            <label for="religion">Religion:</label>
            <input type="text" name="religion" value="<?php echo $personalInfo['Religion']; ?>" required><br>

            <label for="aadharNo">Aadhar No:</label>
            <input type="text" name="aadharNo" value="<?php echo $personalInfo['AadharNo']; ?>" required><br>

            <label for="FathersName">Father's Name:</label>
            <input type="text" name="FathersName" value="<?php echo $personalInfo['FatherName']; ?>" required><br>

            <label for="annualIncome">Annual Income:</label>
            <input type="text" name="annualIncome" value="<?php echo $personalInfo['AnnualIncome']; ?>" required><br>

            <label for="disability">Disability:</label>
            <input type="text" name="disability" value="<?php echo $personalInfo['Disability']; ?>" required><br>

            <h3>Bank Information</h3>
            <label for="bankName">Bank Name:</label>
            <input type="text" name="bankName" value="<?php echo $bankInfo['BankName']; ?>" required><br>

            <label for="accountNo">Account Number:</label>
            <input type="text" name="accountNo" value="<?php echo $bankInfo['AccountNumber']; ?>" required><br>

            <label for="ifsccode">IFSC Code:</label>
            <input type="text" name="ifsccode" value="<?php echo $bankInfo['IFSCCODE']; ?>" required><br>

            <label for="bankAddress">Bank Address:</label>
            <input type="text" name="bankAddress" value="<?php echo $bankInfo['BankBranch']; ?>" required><br>

            <h3>Academic Information</h3>
            <label for="sem1">Semester 1:</label>
            <input type="text" name="sem1" value="<?php echo $academicInfo['Semester1']; ?>" ><br>

            <label for="sem2">Semester 2:</label>
            <input type="text" name="sem2" value="<?php echo $academicInfo['Semester2']; ?>" required><br>

            <label for="sem3">Semester 3:</label>
            <input type="text" name="sem3" value="<?php echo $academicInfo['Semester3']; ?>" required><br>

            <label for="sem4">Semester 4:</label>
            <input type="text" name="sem4" value="<?php echo $academicInfo['Semester4']; ?>" required><br>

            <h3>Project Information</h3>
            <label for="projectTitle">Project Title:</label>
            <input type="text" name="projectTitle" value="<?php echo $projectInfo['ProjectTitle']; ?>" required><br>

            <label for="projectDescription">Project Description:</label>
            <textarea name="projectDescription" required><?php echo $projectInfo['Description']; ?></textarea><br>

            <button type="submit" class="btn">Update Student</button>
            <a href="view_student_details.php?RegNo=<?php echo $regNo; ?>">
                <button type="button" class="btn">Back</button>
            </a>
        </form>
    </div>
</body>
</html>

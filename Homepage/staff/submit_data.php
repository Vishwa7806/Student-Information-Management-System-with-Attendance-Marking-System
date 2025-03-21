<?php
session_start(); // Start the session

// Check if session data exists
if (!isset($_SESSION['regno'])) {
    header("Location: personal_info.html");
    exit();
}

// Database connection (replace with your actual connection details)
$conn = new mysqli("localhost", "root", "", "attendance"); // Replace with your database credentials

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Data from session
$regno = $_SESSION['regno'];
$name = $_SESSION['name'];
$projectTitle = $_SESSION['projectTitle'];
$guideName = $_SESSION['guideName'];
$projectDescription = $_SESSION['projectDescription'];
$sem1 = $_SESSION['sem1'];
$sem2 = $_SESSION['sem2'];
$sem3 = $_SESSION['sem3'];
$sem4 = $_SESSION['sem4'];
$mobile = $_SESSION['mobile'];
$address = $_SESSION['address'];
$community = $_SESSION['community'];
$communityName = $_SESSION['communityName'];
$dob = $_SESSION['dob'];
$gender = $_SESSION['gender'];
$email = $_SESSION['email'];
$religion = $_SESSION['religion'];
$aadhar = $_SESSION['aadhar'];
$batch = $_SESSION['batch'];
$fatherName = $_SESSION['fatherName'];
$income = $_SESSION['income'];
$disability = $_SESSION['disability'];
$bankName = $_SESSION['bankName'];
$accountNo = $_SESSION['accountNo'];
$ifscCode = $_SESSION['ifscCode'];
$bankAddress = $_SESSION['bankAddress'];

// Insert data into respective tables

// Insert Personal Info
$query = "INSERT INTO personal_info (RegisterNo, Name, MobileNo, Address, Community, CommunityName, DateOfBirth, Gender, Email, Religion, AadharNo, Batch, FatherName, AnnualIncome, Disability) 
          VALUES ('$regno', '$name', '$mobile', '$address', '$community', '$communityName', '$dob', '$gender', '$email', '$religion', '$aadhar', '$batch','$fatherName', '$income', '$disability')";
$conn->query($query);

// Insert Bank Info
$query = "INSERT INTO bank_info (RegisterNo, BankName, AccountNumber, IFSCCODE, BankBranch) 
          VALUES ('$regno', '$bankName', '$accountNo', '$ifscCode', '$bankAddress')";
$conn->query($query);

// Insert Academic Info
$query = "INSERT INTO academic_info (RegisterNo, Semester1, Semester2, Semester3, Semester4) 
          VALUES ('$regno', '$sem1', '$sem2', '$sem3', '$sem4')";
$conn->query($query);

// Insert Project Info
$query = "INSERT INTO project_info (RegisterNo, ProjectTitle, GuideName,Description) 
          VALUES ('$regno', '$projectTitle', '$guideName', '$projectDescription')";
$conn->query($query);

// Close connection
$conn->close();

// Clear session data
session_unset();
session_destroy();

// Redirect to a success page
header("Location: success.html");
exit();
?>

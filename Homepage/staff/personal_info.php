<?php
session_start(); // Start the session

// Database connection
$servername = "localhost";
$username = "root";  // Update with your MySQL username
$password = "";      // Update with your MySQL password
$dbname = "attendance"; // Your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// If form is submitted, store data in session and check if regno exists
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $regno = $_POST['regno'];

    // Check if Register Number exists in the student_user table
    $stmt = $conn->prepare("SELECT RegisterNo FROM student_user WHERE RegisterNo = ?");
    $stmt->bind_param("s", $regno);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // If the register number exists, store the form data in session
        $_SESSION['regno'] = $_POST['regno'];
        $_SESSION['name'] = $_POST['name'];
        $_SESSION['mobile'] = $_POST['mobile'];
        $_SESSION['address'] = $_POST['address'];
        $_SESSION['community'] = $_POST['community'];
        $_SESSION['communityName'] = $_POST['communityName'];
        $_SESSION['dob'] = $_POST['dob'];
        $_SESSION['gender'] = $_POST['gender'];
        $_SESSION['email'] = $_POST['email'];
        $_SESSION['religion'] = $_POST['religion'];
        $_SESSION['aadhar'] = $_POST['aadhar'];
        $_SESSION['batch'] = $_POST['batch'];
        $_SESSION['fatherName'] = $_POST['fatherName'];
        $_SESSION['income'] = $_POST['income'];
        $_SESSION['disability'] = $_POST['disability'];

        // Redirect to next page (bank_info.html)
        header("Location: bank_info.php");
        exit();
    } else {
        // If the Register Number doesn't exist, show an alert
        echo "<script>alert('Register Number not found. Please check and try again.');</script>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Personal Information</title>
    <link rel="stylesheet" href="../css/personal_info.css">
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

<div class="container">
    <h1>Student Personal Information</h1>
    <form id="studentForm" action="personal_info.php" method="POST">
        <div class="form-row">
            <div class="form-group">
                <label for="regno">Register Number</label>
                <input type="text" id="regno" name="regno" required value="<?php echo isset($_SESSION['regno']) ? $_SESSION['regno'] : ''; ?>">
            </div>
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" required value="<?php echo isset($_SESSION['name']) ? $_SESSION['name'] : ''; ?>">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="mobile">Mobile Number</label>
                <input type="text" id="mobile" name="mobile" required value="<?php echo isset($_SESSION['mobile']) ? $_SESSION['mobile'] : ''; ?>">
            </div>
            <div class="form-group">
                <label for="address">Address</label>
                <textarea id="address" name="address" rows="2" required><?php echo isset($_SESSION['address']) ? $_SESSION['address'] : ''; ?></textarea>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="community">Community</label>
                <input type="text" id="community" name="community" required value="<?php echo isset($_SESSION['community']) ? $_SESSION['community'] : ''; ?>">
            </div>
            <div class="form-group">
                <label for="communityName">Community Name</label>
                <input type="text" id="communityName" name="communityName" value="<?php echo isset($_SESSION['communityName']) ? $_SESSION['communityName'] : ''; ?>">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="dob">Date of Birth</label>
                <input type="date" id="dob" name="dob" required value="<?php echo isset($_SESSION['dob']) ? $_SESSION['dob'] : ''; ?>">
            </div>
            <div class="form-group">
                <label for="gender">Gender</label>
                <select id="gender" name="gender" required>
                    <option value="">Select</option>
                    <option value="Male" <?php echo isset($_SESSION['gender']) && $_SESSION['gender'] == 'Male' ? 'selected' : ''; ?>>Male</option>
                    <option value="Female" <?php echo isset($_SESSION['gender']) && $_SESSION['gender'] == 'Female' ? 'selected' : ''; ?>>Female</option>
                    <option value="Other" <?php echo isset($_SESSION['gender']) && $_SESSION['gender'] == 'Other' ? 'selected' : ''; ?>>Other</option>
                </select>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required value="<?php echo isset($_SESSION['email']) ? $_SESSION['email'] : ''; ?>">
            </div>
            <div class="form-group">
                <label for="religion">Religion</label>
                <input type="text" id="religion" name="religion" value="<?php echo isset($_SESSION['religion']) ? $_SESSION['religion'] : ''; ?>">
            </div>
        </div>
        <div class="form-row">
    <div class="form-group">
        <label for="aadhar">Aadhar Number</label>
        <input type="text" id="aadhar" name="aadhar" required value="<?php echo isset($_SESSION['aadhar']) ? $_SESSION['aadhar'] : ''; ?>">
    </div>
    <div class="form-group">
        <label for="batch">Batch</label>
        <select id="batch" name="batch" required>
            <option value="">Select Batch</option>
            <option value="2023-2025" <?php echo isset($_SESSION['batch']) && $_SESSION['batch'] == '2023-2025' ? 'selected' : ''; ?>>2023-2025</option>
            <option value="2024-2026" <?php echo isset($_SESSION['batch']) && $_SESSION['batch'] == '2024-2026' ? 'selected' : ''; ?>>2024-2026</option>
        </select>
    </div>
</div>

<div class="form-row">
    <div class="form-group">
        <label for="fatherName">Father's Name</label>
        <input type="text" id="fatherName" name="fatherName" required value="<?php echo isset($_SESSION['fatherName']) ? $_SESSION['fatherName'] : ''; ?>">
    </div>
    <div class="form-group">
        <label for="income">Annual Income</label>
        <input type="number" id="income" name="income" required value="<?php echo isset($_SESSION['income']) ? $_SESSION['income'] : ''; ?>">
    </div>
</div>

<div class="form-row">
    <div class="form-group">
        <label for="disability">Disability (if any)</label>
        <input type="text" id="disability" name="disability" value="<?php echo isset($_SESSION['disability']) ? $_SESSION['disability'] : ''; ?>">
    </div>
</div>

        <div class="form-actions">
            <button type="submit" class="btn save-next">Save & Next</button>
            <button type="reset" class="btn clear">Clear</button>
        </div>
    </form>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Restore values from sessionStorage if available
    var fields = ["regno", "name", "mobile", "address", "community", "communityName", "dob", 
                  "gender", "email", "religion", "aadhar", "fatherName", "income", "disability"];
    
    fields.forEach(function(field) {
        if (sessionStorage.getItem(field)) {
            document.getElementById(field).value = sessionStorage.getItem(field);
        }
    });
});

// Prevent Register Number from Clearing on Reset
document.getElementById("studentForm").addEventListener("reset", function() {
    setTimeout(function() {
        var regno = sessionStorage.getItem("regno");
        if (regno) {
            document.getElementById("regno").value = regno;
        }
    }, 10);
});
</script>

</body>
</html>

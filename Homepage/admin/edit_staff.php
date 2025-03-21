<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "attendance";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['ID'])) {
    $id = $_GET['ID'];

    // Fetch existing staff data
    $sql = "SELECT * FROM staff_user WHERE ID='$id'";
    $result = $conn->query($sql);
    $staff = $result->fetch_assoc();
} else {
    // Redirect if no staff ID is passed
    header("Location: staff_details.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $staffName = $_POST["StaffName"];
    $username = $_POST["UserName"];
    $email = $_POST["Email"];
    $phoneNo = $_POST["PhoneNo"];
    $departmentID = $_POST["DepartmentID"];
    $DepartmentName = $_POST["DepartmentName"];
    $Designation = $_POST["Designation"];


    // SQL query for updating staff
    $sql = "UPDATE staff_user SET StaffName='$staffName', UserName='$username', Email='$email', PhoneNo='$phoneNo', DepartmentID='$departmentID', DepartmentName='$DepartmentName', Designation='$Designation' WHERE ID='$id'";

    if ($conn->query($sql) === TRUE) {
        // Redirect to staff_details.php to reflect the update
        echo "<script>
                alert('Staff updated successfully!');
                window.location.href='staff_details.php';
              </script>";
    } else {
        echo "<script>
                alert('Error updating: " . $conn->error . "');
              </script>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Staff</title>
    <style>
        /* Basic Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }

        header {
            text-align: center;
            margin-bottom: 30px;
        }

        h1 {
            color: #333;
        }

        /* Form Styling */
        .form-container {
            width: 60%;
            margin: 0 auto;
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .form-container label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
        }

        .form-container input {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .form-container button {
            width: 100%;
            padding: 12px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }

        .form-container button:hover {
            background-color: #45a049;
        }

        .form-container a {
            display: inline-block;
            margin-top: 15px;
            color: #4CAF50;
            text-decoration: none;
            font-size: 16px;
        }

        .form-container a:hover {
            text-decoration: underline;
        }

        /* Mobile responsiveness */
        @media (max-width: 768px) {
            .form-container {
                width: 90%;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Edit Staff Details</h1>
    </header>

    <div class="form-container">
        <form method="POST">
            <label for="StaffName">Staff Name:</label>
            <input type="text" name="StaffName" id="StaffName" value="<?php echo $staff['StaffName']; ?>" required>

            <label for="UserName">Username:</label>
            <input type="text" name="UserName" id="UserName" value="<?php echo $staff['UserName']; ?>" required>

            <label for="Email">Email:</label>
            <input type="email" name="Email" id="Email" value="<?php echo $staff['Email']; ?>" required>

            <label for="PhoneNo">Phone No:</label>
            <input type="text" name="PhoneNo" id="PhoneNo" value="<?php echo $staff['PhoneNo']; ?>" required>
   
            <label for="DepartmentID">Department ID:</label>
            <input type="text" name="DepartmentID" id="DepartmentID" value="<?php echo $staff['DepartmentID']; ?>" required>

            <label for="DepartmentName">Department Name:</label>
            <input type="text" name="DepartmentName" id="DepartmentName" value="<?php echo $staff['DepartmentName']; ?>" required>

            <label for="Designation">Designation:</label>
            <input type="text" name="Designation" id="Designation" value="<?php echo $staff['Designation']; ?>" required>

            <button type="submit">Update Staff</button>
        </form>
    </div>

</body>
</html>

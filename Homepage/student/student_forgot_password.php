<?php
// Database connection
$servername = "localhost"; // Your DB server
$username = "root";        // Your DB username
$password = "";            // Your DB password
$dbname = "attendance";    // Your database name

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize a variable for the message to display
$message = '';

// Process the password change when the form is submitted
if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $mobile_no = $_POST['mobile_no'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if all fields are filled
    if (empty($email) || empty($mobile_no) || empty($new_password) || empty($confirm_password)) {
        $message = "All fields are required.";
    } else {
        // Password validation regex
        $password_pattern = "/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).{8,}$/";

        // Validate password against the security rules
        if (!preg_match($password_pattern, $new_password)) {
            $message = "Password must be at least 8 characters long, with at least one uppercase letter, one lowercase letter, one number, and one special character.";
        } else {
            // Check if email and mobile number match in personal_info table
            $sql = "SELECT RegisterNo FROM personal_info WHERE Email = ? AND MobileNo = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $email, $mobile_no);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // If both email and mobile match
                if ($new_password === $confirm_password) {
                    // Get the RegNo from the matched records
                    $reg_no = $result->fetch_assoc()['RegisterNo'];

                    // Update the password in the student_user table
                    $sql = "UPDATE student_user SET Password = ? WHERE RegisterNo = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("si", $new_password, $reg_no);
                    if ($stmt->execute()) {
                        $message = "Password updated successfully!";
                    } else {
                        $message = "Failed to update password. Please try again.";
                    }
                } else {
                    $message = "New password and confirmation do not match.";
                }
            } else {
                $message = "Email or Mobile Number does not match our records.";
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Forgot Password</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        /* Left Section */
        .left-section {
            flex: 1;
            background-color: lightgreen;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 30px;
            animation: slideInLeft 1s ease-in-out;
        }

        .left-section img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
        }

        /* Right Section */
        .right-section {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 30px;
            background: linear-gradient(135deg, #6a82fb, #fc5c7d); /* Gradient background */
            box-shadow: -4px 0px 15px rgba(0, 0, 0, 0.1);
        }

        .right-section .form-container {
            width: 100%;
            max-width: 400px;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            background: #ffffff;
            text-align: center;
        }

        .right-section h2 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
        }

        .right-section input[type="email"], .right-section input[type="text"], .right-section input[type="password"], .right-section input[type="submit"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .right-section input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .right-section input[type="submit"]:hover {
            background-color: #45a049;
        }

        .message {
            margin-top: 20px;
            color: #f8d7da;
        }

        /* Animations */
        @keyframes slideInLeft {
            0% {
                transform: translateX(-100%);
            }
            100% {
                transform: translateX(0);
            }
        }
    </style>
</head>
<body>

    <!-- Left Section with Animated Image -->
    <div class="left-section">
        <img src="../images/forgot.gif" alt="Shocked Boy">
    </div>

    <!-- Right Section with Form -->
    <div class="right-section">
        <div class="form-container">
            <h2>Student Forgot Password</h2>
            
            <!-- Message Display -->
            <?php if ($message): ?>
                <div class="message"><?php echo $message; ?></div>
            <?php endif; ?>

            <form action="student_forgot_password.php" method="POST">
                <input type="email" name="email" placeholder="Enter your Email" required>
                <input type="text" name="mobile_no" placeholder="Enter your Mobile Number" required>
                <input type="password" name="new_password" placeholder="New Password" required>
                <input type="password" name="confirm_password" placeholder="Confirm New Password" required>
                <input type="submit" name="submit" value="Update Password">
            </form>
        </div>
    </div>

</body>
</html>

<?php
// Close the database connection
$conn->close();
?>

<?php
// Start the session
session_start();

// Database connection
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "attendance"; 

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the register number from the session
$RegisterNo = $_SESSION['register_no']; // Use the session to get the RegisterNo

// Fetch the current password from the database (only when RegisterNo exists)
$sql = "SELECT Password FROM student_user WHERE RegisterNo = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $RegisterNo);  // Use "s" for string type (RegisterNo is Varchar)
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc(); // Fetch user data

// Handle form submission for password update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the old and new passwords
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate the new password (min 8 chars, 1 uppercase, 1 lowercase, 1 special char, 1 number, including underscore)
    if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&_\-])[A-Za-z\d@$!%*?&_\-]{8,}$/", $new_password)) {
        $error_message = "Password must be at least 8 characters long, with at least one uppercase letter, one lowercase letter, one number, and one special character (including underscore).";
    } elseif ($new_password != $confirm_password) {
        $error_message = "New passwords do not match.";
    } elseif ($old_password == $user['Password']) {
        // Update the password in the database
        $update_sql = "UPDATE student_user SET Password = ? WHERE RegisterNo = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("ss", $new_password, $RegisterNo);

        if ($update_stmt->execute()) {
            $success_message = "Password updated successfully!";
        } else {
            $error_message = "Error updating password. Please try again.";
        }
    } else {
        $error_message = "Incorrect old password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <style>
        .container {
            width: 40%;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }


/* Sidebar styling */
.sidebar {
    width: 220px;
    height: 100vh;
    background: #2c3e50;
    color: white;
    position: fixed;
    padding: 30px 20px;
    top: 0;
    left: 0;
    margin: 0;
    font-family: 'Arial', sans-serif;
    box-shadow: 3px 0 15px rgba(0, 0, 0, 0.2);
}

.sidebar ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.sidebar ul li {
    margin: 20px 0;
}

.sidebar ul li a {
    color: white;
    text-decoration: none;
    font-size: 20px;
    display: block;
    padding: 12px;
    border-radius: 8px;
    transition: all 0.3s ease;
    font-weight: bold;
}

.sidebar ul li a:hover {
    background: #1abc9c;
    transform: translateX(10px);
}

        label {
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 10px;
            margin: 5px 0 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            border-radius: 5px;
        }

        button:hover {
            background-color: #45a049;
        }

        .error {
            color: red;
        }

        .success {
            color: green;
        }
    </style>
</head>
<body>

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


<div class="container">
    <h2>Change Your Password</h2>

    <form action="settings.php" method="POST">
        <label for="old_password">Old Password:</label>
        <input type="password" id="old_password" name="old_password" required>

        <label for="new_password">New Password:</label>
        <input type="password" id="new_password" name="new_password" required>

        <label for="confirm_password">Confirm New Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" required>

        <button type="submit">Update Password</button>
    </form>

    <div class="message">
        <?php
            // Display any error or success messages
            if (isset($error_message)) {
                echo "<p class='error'>$error_message</p>";
            }

            if (isset($success_message)) {
                echo "<p class='success'>$success_message</p>";
            }
        ?>
    </div>
</div>

</body>
</html>

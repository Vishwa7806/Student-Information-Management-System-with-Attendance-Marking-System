<?php
// Database connection
$servername = "localhost";
$username = "root";  // Use your database username
$password = "";  // Use your database password
$dbname = "attendance";       // Your database name

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $reg_no = $_POST['reg_no'];
    $name = $_POST['name'];
    $username = $_POST['username'];  // Corrected field name
    $password = $_POST['password'];
    $email = $_POST['email'];

    // Validate input (you can add more validation if needed)
    if (!empty($reg_no) && !empty($name) && !empty($username) && !empty($password) && !empty($email)) {
        // Prepare the SQL query to insert data into the student_user table
        $query = "INSERT INTO student_user (RegisterNo, Name, UserName, Password, Email) VALUES ('$reg_no', '$name', '$username', '$password', '$email')";

        // Execute the query
        if (mysqli_query($conn, $query)) {
            // Redirect to a success page or display a success message
            echo "Student user created successfully!";
            // You can redirect with header('Location: success_page.php'); if needed
        } else {
            // If query fails, display an error message
            echo "Error: " . $query . "<br>" . mysqli_error($conn);
        }
    } else {
        echo "All fields are required!";
    }
}

// Close the database connection
mysqli_close($conn);
?>

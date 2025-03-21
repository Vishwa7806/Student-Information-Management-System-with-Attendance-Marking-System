<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "attendance";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to get the attendance hour based on the current time
function getAttendanceHour() {
    date_default_timezone_set("Asia/Kolkata"); // Ensure correct timezone
    $currentTime = date("H:i"); // Get time in 24-hour format

    echo "Debug - Current Time: " . $currentTime; // Debugging statement
    if ($currentTime < "09:00" || $currentTime > "13:40") {
        return "Unknown Hour";
    }

    $hourSlots = [
        "09:00" => "1st Hour", "10:01" => "2nd Hour",
        "11:00" => "3rd Hour", "12:01" => "4th Hour",
        "12:51" => "5th Hour"
    ];

    foreach ($hourSlots as $start => $label) {
        if ($currentTime >= $start) {
            $hour = $label;
        } else {
            break;
        }
    }
    return $hour ?? "Unknown Hour";
}

// Handling form submission for attendance
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_attendance'])) {
    $batch = $_POST['batch'];
    $date = date('Y-m-d'); // Get today's date
    $hour = getAttendanceHour(); // Get the current hour for attendance

    // If the current time is outside the allowed working hours (before 09:00 AM or after 01:40 PM)
    if ($hour === "Unknown Hour") {
        echo "<script>alert('Attendance can only be marked between 09:00 AM and 01:40 PM.');</script>";
        exit;
    }

    // Loop through the students' attendance status
foreach ($_POST['attendance'] as $registerNo => $status) {
    // Get the student's name from the form submission
    $name = isset($_POST['name'][$registerNo]) ? $_POST['name'][$registerNo] : null;

    // Check if name is available before inserting
    if ($name) {
        // Insert the attendance record into the attendance_record table
        $stmt = $conn->prepare("INSERT INTO attendance_record (RegisterNo, Name, Status, Date, Hour, Batch) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $registerNo, $name, $status, $date, $hour, $batch);
        $stmt->execute();
        $stmt->close();
    } else {
        echo "Name not found for Register No: " . $registerNo;
    }
}

    // Redirect or give a success message
    echo "<script>alert('Attendance marked successfully!');</script>";
    exit;
}

// Fetch students from batch
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['batch']) && !isset($_POST['submit_attendance'])) {
    $batch = $_POST['batch'];
    $students = getStudentsFromBatch($batch);
    echo json_encode($students);
    exit;
}

function getStudentsFromBatch($batch) {
    global $conn;
    $students = [];
    $stmt = $conn->prepare("SELECT RegisterNo, Name FROM personal_info WHERE Batch = ?");
    $stmt->bind_param("s", $batch);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }
    $stmt->close();
    return $students;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mark Attendance</title>
    <link rel="stylesheet" href="../css/mark_attendance.css">
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

    <!-- Mark Attendance Form -->
    <div class="container">
        <h2>Mark Attendance</h2>
        <form action="mark_attendance.php" method="POST" id="attendanceForm">
            <!-- Batch Dropdown -->
            <label for="batch">Select Batch:</label>
            <select id="batch" name="batch" required disabled>
                <option value="">Select Batch</option>
                <option value="2024-2026">2024-2026</option>
                <option value="2023-2025">2023-2025</option>
<!--                <option value="2023-2025">2023-2025</option>  -->
            </select>

            <!-- Get Students Button -->
            <button type="button" name="get_students" id="getStudentsBtn" onclick="getStudents()" disabled>Get Students</button>

            <!-- Mark Attendance Button -->
            <button type="button" name="mark_attendance" id="markAttendanceBtn" onclick="startMarkingAttendance()">Mark Attendance</button>

            <!-- Timer Display -->
            <div id="timerDisplay">05:00</div>

            <!-- Student Table -->
            <table id="attendanceTable">
                <thead>
                    <tr>
                        <th>Register No</th>
                        <th>Name</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody id="studentTableBody">
                    <!-- Students will be populated here from JavaScript -->
                </tbody>
            </table>

            <!-- Submit Attendance Button -->
            <button type="submit" name="submit_attendance" id="submitAttendanceBtn" disabled>Submit Attendance</button>
        </form>
    </div>

    <!-- JavaScript (Embedded in PHP) -->
    <script>
        let timer;
        let timeLeft = 5 * 60; // 5 minutes in seconds
        let timerRunning = false;

        function startMarkingAttendance() {
            // Enable all components
            document.getElementById("batch").disabled = false;
            document.getElementById("getStudentsBtn").disabled = false;
            document.getElementById("submitAttendanceBtn").disabled = false;
            document.getElementById("markAttendanceBtn").disabled = true; // Disable the Mark Attendance button

            // Start the timer if not already started
            if (!timerRunning) {
                startTimer();
                timerRunning = true;
            }
        }

        function startTimer() {
            // Update the timer every second
            timer = setInterval(function() {
                if (timeLeft <= 0) {
                    clearInterval(timer); // Stop the timer when it reaches zero
                    document.getElementById('submitAttendanceBtn').click(); // Auto-submit the form
                } else {
                    timeLeft--;
                    let minutes = Math.floor(timeLeft / 60);
                    let seconds = timeLeft % 60;
                    document.getElementById('timerDisplay').textContent = `${formatTime(minutes)}:${formatTime(seconds)}`;
                }
            }, 1000);
        }

        function formatTime(time) {
            return time < 10 ? `0${time}` : time;
        }

        // Fetch students when "Get Students" button is clicked
        function getStudents() {
            const batch = document.getElementById("batch").value;
            if (batch) {
                // Use fetch to call PHP script and get students data
                fetch("mark_attendance.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded"
                    },
                    body: "batch=" + batch // Pass the batch to the PHP script
                })
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        let studentRows = '';
                        data.forEach(student => {
                            studentRows += `
                                <tr>
                                    <td>${student.RegisterNo}</td>
                                    <td>${student.Name}</td>
                                    <td>
                                        <input type="radio" name="attendance[${student.RegisterNo}]" value="Present"> Present
                                        <input type="radio" name="attendance[${student.RegisterNo}]" value="Absent"> Absent
		    <input type="hidden" name="name[${student.RegisterNo}]" value="${student.Name}">
                                    </td>
                                </tr>
                            `;
                        });
                        document.getElementById("studentTableBody").innerHTML = studentRows;
                    } else {
                        alert("No students found for this batch");
                    }
                })
                .catch(error => console.error('Error fetching students:', error));
            }
        }
    </script>

</body>
</html>

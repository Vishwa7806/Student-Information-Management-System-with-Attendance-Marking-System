<?php
$servername = "localhost";
$username = "root"; // Change if needed
$password = ""; // Change if needed
$dbname = "attendance";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get filter values from URL
$filterYear = isset($_GET['year']) ? $_GET['year'] : '';
$filterCommunity = isset($_GET['community']) ? $_GET['community'] : '';

// Pagination setup
$recordsPerPage = isset($_GET['rowsPerPage']) ? (int)$_GET['rowsPerPage'] : 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $recordsPerPage;

// Query to fetch merged student data with filters
$sql = "SELECT p.*, b.BankName, b.AccountNumber, b.IFSCCODE, b.BankBranch, 
        a.Semester1, a.Semester2, a.Semester3, a.Semester4, pr.ProjectTitle, pr.GuideName, pr.Description
        FROM personal_info p
        LEFT JOIN bank_info b ON p.RegisterNo = b.RegisterNo
        LEFT JOIN academic_info a ON p.RegisterNo = a.RegisterNo
        LEFT JOIN project_info pr ON p.RegisterNo = pr.RegisterNo
        WHERE 1";

if ($filterYear) {
    $sql .= " AND p.Batch = '$filterYear'";
}

if ($filterCommunity) {
    $sql .= " AND p.Community = '$filterCommunity'";
}

$totalRecordsQuery = $conn->query($sql);
$totalRecords = $totalRecordsQuery->num_rows;

$sql .= " LIMIT $offset, $recordsPerPage";

$result = $conn->query($sql);

// Handle deletion
if (isset($_GET['delete'])) {
    $regNoToDelete = $_GET['delete'];

    $conn->begin_transaction();
    try {
        $conn->query("DELETE FROM student_user WHERE RegisterNo = '$regNoToDelete'");
        $conn->query("DELETE FROM personal_info WHERE RegisterNo = '$regNoToDelete'");
        $conn->query("DELETE FROM bank_info WHERE RegisterNo = '$regNoToDelete'");
        $conn->query("DELETE FROM academic_info WHERE RegisterNo = '$regNoToDelete'");
        $conn->query("DELETE FROM project_info WHERE RegisterNo = '$regNoToDelete'");

        $conn->commit();
        echo "<script>alert('Record deleted successfully.');</script>";
    } catch (Exception $e) {
        $conn->rollback();
        echo "<script>alert('Error deleting record: " . $e->getMessage() . "');</script>";
    }
}


$totalPages = ceil($totalRecords / $recordsPerPage);
$previousPage = $page - 1;
$nextPage = $page + 1;
$disablePrevious = $page == 1 ? 'disabled' : '';
$disableNext = $page == $totalPages ? 'disabled' : '';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Details</title>
    <link rel="stylesheet" href="../css/student_details.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>


</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
          <h2 style= "color:white; text-align:left;">Menu</h2>
        <ul>
            <li><a href="admin_dashboard.php">Dashboard</a></li>
            <li><a href="staff_details.php">Staff Details</a></li>
            <li><a href="student_details.php">Student Details</a></li>
            <li><a href="view_attendance.php">Attendance Details</a></li>
        </ul>
    </div>
    <!-- Main Content -->
    <div class="main-content">
        <header>
            <h1>Student Details</h1>
        </header>

 <div class="filter-container">
            <div class="filter-group">
                <label for="year"><b>Year of Study:</b></label>
                <select id="year" name="year">
                    <option value="">Select Year</option>
                    <option value="2024-2026" <?php if ($filterYear == "2024-2026") echo "selected"; ?>>2024-2026</option>
                    <option value="2023-2025" <?php if ($filterYear == "2023-2025") echo "selected"; ?>>2023-2025</option>
                    <option value="2022-2024" <?php if ($filterYear == "2022-2024") echo "selected"; ?>>2022-2024</option>
                    <option value="2021-2023" <?php if ($filterYear == "2021-2023") echo "selected"; ?>>2021-2023</option>
                </select>
            </div>

            <div class="filter-group">
                <label for="community"><b>Community:</b></label>
                <select id="community" name="community">
                    <option value="">Select Community</option>
                    <option value="General" <?php if ($filterCommunity == "General") echo "selected"; ?>>General</option>
                    <option value="OBC" <?php if ($filterCommunity == "OBC") echo "selected"; ?>>OBC</option>
                    <option value="SC" <?php if ($filterCommunity == "SC") echo "selected"; ?>>SC</option>
                    <option value="ST" <?php if ($filterCommunity == "ST") echo "selected"; ?>>ST</option>
                </select>
            </div>

            <button onclick="applyFilters()">Apply Filter</button>
        </div>

        <!-- Search Bar -->
        <div class="search-box">
            <input type="text" id="searchInput" placeholder="Search by Name or Register No" onkeyup="searchTable()">
        </div>

        <!-- Table Section -->
        <div class="table-container">
            <table id="studentTable">
                <thead>
                    <tr>
                        <th>Reg No</th>
                        <th>Name</th>
                        <th>Mobile No</th>
                        <th>Address</th>
                        <th>Community</th>
                        <th>Community Name</th>
                        <th>D.O.B</th>
                        <th>Gender</th>
                        <th>Email</th>
                        <th>Religion</th>
                        <th>Aadhar No</th>
                        <th>Batch</th>
                        <th>Father's Name</th>
                        <th>Annual Income</th>
                        <th>Disability</th>
                        <th>Bank Name</th>
                        <th>Account No</th>
                        <th>IFSC Code</th>
                        <th>Bank Address</th>
                        <th>Sem 1</th>
                        <th>Sem 2</th>
                        <th>Sem 3</th>
                        <th>Sem 4</th>
                        <th>Project Title</th>
                        <th>Guide</th>
                        <th>Project Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
<?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                <td>{$row['RegisterNo']}</td>
                                <td>{$row['Name']}</td>
                                <td>{$row['MobileNo']}</td>
                                <td>{$row['Address']}</td>
                                <td>{$row['Community']}</td>
                                <td>{$row['CommunityName']}</td>
                                <td>{$row['DateOfBirth']}</td>
                                <td>{$row['Gender']}</td>
                                <td>{$row['Email']}</td>
                                <td>{$row['Religion']}</td>
                                <td>{$row['AadharNo']}</td>
                                <td>{$row['Batch']}</td>
                                <td>{$row["FatherName"]}</td>
                                <td>{$row['AnnualIncome']}</td>
                                <td>{$row['Disability']}</td>
                                <td>{$row['BankName']}</td>
                                <td>{$row['AccountNumber']}</td>
                                <td>{$row['IFSCCODE']}</td>
                                <td>{$row['BankBranch']}</td>
                                <td>{$row['Semester1']}</td>
                                <td>{$row['Semester2']}</td>
                                <td>{$row['Semester3']}</td>
                                <td>{$row['Semester4']}</td>
                                <td>{$row['ProjectTitle']}</td>
                                <td>{$row['GuideName']}</td>
                                <td>{$row['Description']}</td>
                                <td>
                                    
                                    <a href='student_details.php?delete={$row['RegisterNo']}' onclick='return confirm(\"Are you sure you want to delete this student?\")'>
                                        <button>Delete</button>
                                    </a>
                                </td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='15'>No records found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>


    <!-- Pagination Controls (Placed below the table) -->
<div class="bottom-controls">
    <!-- Rows per page dropdown -->
    <div class="pagination-dropdown">
        <label for="rowsPerPage">Rows per page:</label>
        <select id="rowsPerPage" name="rowsPerPage" onchange="changeRowsPerPage()">
            <option value="15" <?php if ($recordsPerPage == 15) echo 'selected'; ?>>15</option>
            <option value="20" <?php if ($recordsPerPage == 20) echo 'selected'; ?>>20</option>
            <option value="50" <?php if ($recordsPerPage == 50) echo 'selected'; ?>>50</option>
            <option value="100" <?php if ($recordsPerPage == 100) echo 'selected'; ?>>100</option>
            <option value="150" <?php if ($recordsPerPage == 150) echo 'selected'; ?>>150</option>
        </select>
    </div>

    <!-- Previous / Next Navigation -->
    <div class="pagination-buttons">
        <button onclick="changePage(-1)" <?php echo $disablePrevious; ?>>Previous</button>
        <button onclick="changePage(1)" <?php echo $disableNext; ?>>Next</button>
    </div>
</div>



        <!-- Export Button -->
        <div class="export-btn">
            <button onclick="exportToExcel()">EXPORT</button>
        </div>
    </div>

    <script>


        function changePage(direction) {
            let currentPage = new URLSearchParams(window.location.search).get('page') || 1;
            let newPage = parseInt(currentPage) + direction;
            if (newPage < 1) return;  // Prevent going below page 1

            // Update the URL with the new page number
            const url = new URL(window.location.href);
            url.searchParams.set('page', newPage);
            window.location.href = url.toString();
        }

        function changeRowsPerPage() {
            let rowsPerPage = document.getElementById("rowsPerPage").value;
            const url = new URL(window.location.href);
            url.searchParams.set('rowsPerPage', rowsPerPage);  // Update rowsPerPage in the URL
            url.searchParams.set('page', 1);  // Reset to the first page when changing rows per page
            window.location.href = url.toString();
        }



// Export to Excel function
        function exportToExcel() {
            const table = document.getElementById("studentTable");
            const wb = XLSX.utils.table_to_book(table, { sheet: "Student Details" });
            XLSX.writeFile(wb, "student_details.xlsx");
        }

           function applyFilters() {
    let year = document.getElementById("year").value;
    let community = document.getElementById("community").value;
    // Add quotes around the URL string
    window.location.href = `student_details.php?year=${year}&community=${community}`;
}

            function searchTable() {
            const input = document.getElementById("searchInput").value.toLowerCase();
            const rows = document.querySelectorAll("#studentTable tbody tr");



            rows.forEach(row => {
                const name = row.cells[1].textContent.toLowerCase();
                const regNo = row.cells[0].textContent.toLowerCase();
                row.style.display = (name.includes(input) || regNo.includes(input)) ? "" : "none";
            });
}
        
    </script>
<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "attendance";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

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
    <link rel="stylesheet" href="../css/view_student_details.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
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
                    <option value="BC" <?php if ($filterCommunity == "BC") echo "selected"; ?>>BC</option>
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
                <?php if ($result->num_rows > 0) { while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['RegisterNo']; ?></td>
                    <td><?php echo $row['Name']; ?></td>
                    <td><?php echo $row['MobileNo']; ?></td>
                    <td><?php echo $row['Address']; ?></td>
                    <td><?php echo $row['Community']; ?></td>
                    <td><?php echo $row['CommunityName']; ?></td>
                    <td><?php echo $row['DateOfBirth']; ?></td>
                    <td><?php echo $row['Gender']; ?></td>
                    <td><?php echo $row['Email']; ?></td>
                    <td><?php echo $row['Religion']; ?></td>
                    <td><?php echo $row['AadharNo']; ?></td>
                    <td><?php echo $row['Batch']; ?></td>
                    <td><?php echo $row['FatherName']; ?></td>
                    <td><?php echo $row['AnnualIncome']; ?></td>
                    <td><?php echo $row['Disability']; ?></td>
                    <td><?php echo $row['BankName']; ?></td>
                    <td><?php echo $row['AccountNumber']; ?></td>
                    <td><?php echo $row['IFSCCODE']; ?></td>
                    <td><?php echo $row['BankBranch']; ?></td>
                    <td><?php echo $row['Semester1']; ?></td>
                    <td><?php echo $row['Semester2']; ?></td>
                    <td><?php echo $row['Semester3']; ?></td>
                    <td><?php echo $row['Semester4']; ?></td>
                    <td><?php echo $row['ProjectTitle']; ?></td>
                    <td><?php echo $row['GuideName']; ?></td>
                    <td><?php echo $row['Description']; ?></td>
                    <td><a href="update_student.php?RegNo=<?php echo $row['RegisterNo']; ?>">Edit</a></td>
                </tr>
                <?php } } else { ?>
                <tr><td colspan="25">No records found</td></tr>
                <?php } ?>
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
        XLSX.writeFile(wb, "Student_Details.xlsx");
    }

    function applyFilters() {
    let year = document.getElementById("year").value;
    let community = document.getElementById("community").value;
    // Add quotes around the URL string
    window.location.href = `view_student_details.php?year=${year}&community=${community}`;
}


    // Search function
    function searchTable() {
        const searchInput = document.getElementById("searchInput").value.toLowerCase();
        const table = document.getElementById("studentTable");
        const tr = table.getElementsByTagName("tr");

        for (let i = 0; i < tr.length; i++) {
            const td = tr[i].getElementsByTagName("td");
            for (let j = 0; j < td.length; j++) {
                if (td[j].innerHTML.toLowerCase().includes(searchInput)) {
                    tr[i].style.display = "";
                    break;
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }

    // Pagination functions
    let currentPage = 1;
    let rowsPerPage = 10;

    function changePage(direction) {
        if (direction === 1) {
            currentPage++;
        } else {
            currentPage--;
        }
        const url = `view_student_details.php?page=${currentPage}`;
        window.location.href = url;
    }

    function changeRowsPerPage() {
        rowsPerPage = document.getElementById("rowsPerPage").value;
        const url = `view_student_details.php?page=${currentPage}&rowsPerPage=${rowsPerPage}`;
        window.location.href = url;
    }
</script>
</body>
</html>


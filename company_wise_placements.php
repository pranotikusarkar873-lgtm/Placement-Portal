<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "placement_system");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// ---------------------------------------------
// DOWNLOAD EXCEL
// ---------------------------------------------
if (isset($_GET['download']) && $_GET['download'] == "excel") {

    header("Content-Type: text/csv");
    header("Content-Disposition: attachment; filename=company_wise_placed_students_" . date('Y-m-d') . ".csv");

    $output = fopen("php://output", "w");

    fputcsv($output, ["Company", "Student Name", "Placement Date", "Package"]);

    $download_sql = "SELECT company_name, student_name, placement_date, package 
                     FROM placed_students ORDER BY company_name";

    $download_result = $conn->query($download_sql);

    while ($row = $download_result->fetch_assoc()) {
        fputcsv($output, [
            $row['company_name'],
            $row['student_name'],
            $row['placement_date'],
            $row['package']
        ]);
    }

    fclose($output);
    exit();
}

// ---------------------------------------------
// FILTER SECTION
// ---------------------------------------------
$selected_company = $_POST['company_name'] ?? '';

// Fetch companies
$companies_result = $conn->query("SELECT DISTINCT company_name FROM placed_students");

// Main query
$sql = "SELECT company_name, student_name, placement_date, package, photo
        FROM placed_students";

if (!empty($selected_company)) {
    $sql .= " WHERE company_name = '" . $conn->real_escape_string($selected_company) . "'";
}

$sql .= " ORDER BY company_name, student_name";

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Company-wise Placed Students</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>

<body class="bg-light">

<div class="container mt-5">

    <!-- HEADER CARD -->
    <div class="card p-4 shadow mb-4 d-flex flex-row justify-content-between align-items-center">
        <h3 class="m-0">Company-wise Placed Students</h3>

        <!-- DOWNLOAD BUTTON WITH DOWN ARROW -->
        <a href="?download=excel" class="btn btn-success btn-sm">
            ⬇️
        </a>
    </div>

    <!-- FILTER & TABLE CARD -->
    <div class="card p-4 shadow">

        <!-- Filter Form -->
        <form method="post" class="row g-3 mb-3">
            <div class="col-md-4">
                <label>Select Company</label>
                <select name="company_name" class="form-select">
                    <option value="">All Companies</option>

                    <?php while ($comp = $companies_result->fetch_assoc()): ?>
                        <option value="<?= htmlspecialchars($comp['company_name']); ?>"
                            <?= ($comp['company_name'] == $selected_company) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($comp['company_name']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="col-md-2 align-self-end">
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
        </form>

        <!-- Report Table -->
        <table class="table table-bordered table-striped">
            <tr class="table-dark">
                <th>Company</th>
                <th>Student Name</th>
                <th>Placement Date</th>
                <th>Package</th>
                <th>Photo</th>
            </tr>

            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['company_name']); ?></td>
                    <td><?= htmlspecialchars($row['student_name']); ?></td>
                    <td><?= htmlspecialchars($row['placement_date']); ?></td>
                    <td><?= htmlspecialchars($row['package']); ?></td>
                    <td>
                        <?php if ($row['photo']): ?>
                            <img src="uploads/<?= htmlspecialchars($row['photo']); ?>" width="50">
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>

            <?php else: ?>
                <tr><td colspan="5" class="text-center">No placed students found.</td></tr>
            <?php endif; ?>
        </table>

    </div>

      <div class="text-center mt-3 mb-5">
        <a href="placement_reports.php" class="btn btn-primary">Back</a>
    </div>
    

</div>

</body>
</html>

<?php $conn->close(); ?>

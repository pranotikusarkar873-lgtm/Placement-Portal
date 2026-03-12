<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "placement_system");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

/* -----------------------------------------
   DOWNLOAD EXCEL (CSV)
------------------------------------------ */
if (isset($_GET['download']) && $_GET['download'] == 'excel') {

    $sql = "SELECT student_name, company_name, job_role, placement_date, package, photo
            FROM placed_students
            ORDER BY id DESC";

    $result = $conn->query($sql);

    $filename = "placed_students_report_" . date('Y-m-d') . ".csv";

    header("Content-Type: text/csv");
    header("Content-Disposition: attachment; filename=\"$filename\"");

    $output = fopen("php://output", "w");

    // CSV headings
    fputcsv($output, ["Student Name", "Company", "Job Role", "Placement Date", "Package", "Photo File"]);

    while ($row = $result->fetch_assoc()) {
        fputcsv($output, [
            $row["student_name"],
            $row["company_name"],
            $row["job_role"],
            $row["placement_date"],
            $row["package"],
            $row["photo"]
        ]);
    }

    fclose($output);
    exit();
}

/* FETCH DATA FOR PAGE */
$sql = "SELECT student_name, company_name, job_role, placement_date, package, photo
        FROM placed_students
        ORDER BY id DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Placed Students Report</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <!-- FontAwesome (for icons) -->
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</head>

<body class="bg-light">

<div class="container mt-5">

    <!-- Header Card (Same style as other reports) -->
    <div class="card p-4 shadow mb-4 d-flex flex-row justify-content-between align-items-center">

        <h3 class="text-primary m-0">
            <i class="fas fa-user-graduate"></i> Placed Students Report
        </h3>

        <!-- TOP-RIGHT DOWNLOAD BUTTON (Same style) -->
        <a href="?download=excel" class="btn btn-success btn-sm">
            ⬇️
        </a>

    </div>

    <!-- Main Table Card -->
    <div class="card p-4 shadow">

        <table class="table table-bordered table-striped">
            <tr class="table-dark">
                <th>Name</th>
                <th>Company</th>
                <th>Position</th>
                <th>Placement Date</th>
                <th>Package</th>
                <th>Photo</th>
            </tr>

            <?php if($result && $result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['student_name']); ?></td>
                    <td><?= htmlspecialchars($row['company_name']); ?></td>
                    <td><?= htmlspecialchars($row['job_role']); ?></td>
                    <td><?= htmlspecialchars($row['placement_date']); ?></td>
                    <td><?= htmlspecialchars($row['package']); ?></td>

                    <td>
                        <?php if($row['photo']): ?>
                            <img src="uploads/<?= htmlspecialchars($row['photo']); ?>" width="50" class="rounded">
                        <?php else: ?>
                            <span class="text-muted">No Photo</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="6" class="text-center text-muted">No placed students found.</td></tr>
            <?php endif; ?>
        </table>

    </div>

    <div class="text-center mt-3">
        <a href="placement_reports.php" class="btn btn-secondary">Back</a>
    </div>

</div>

</body>
</html>

<?php $conn->close(); ?>

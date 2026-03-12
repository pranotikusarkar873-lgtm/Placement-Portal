<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "placement_system");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Detect date column dynamically
$date_column = '';
$result_columns = $conn->query("SHOW COLUMNS FROM jobs");
while ($col = $result_columns->fetch_assoc()) {
    $col_name = strtolower($col['Field']);
    if (in_array($col_name, ['created_at', 'posted_at', 'date_posted', 'posted_date'])) {
        $date_column = $col['Field'];
        break;
    }
}
if (!$date_column) $date_column = 'id'; // fallback

// Export to Excel
if (isset($_POST['export_excel'])) {
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=company_jobs.xls");

    echo "Job ID\tJob Title\tCompany Name\tPosted At\n";

    $sql = "SELECT j.id AS job_id, j.job_title, j.company_name, j.$date_column
            FROM jobs j
            ORDER BY j.$date_column DESC";
    $result = $conn->query($sql);

    while ($row = $result->fetch_assoc()) {
        echo $row['job_id'] . "\t" . $row['job_title'] . "\t" . $row['company_name'] . "\t" . $row[$date_column] . "\n";
    }
    exit();
}

// Fetch jobs for display
$sql = "SELECT j.id AS job_id, j.job_title, j.company_name, j.$date_column
        FROM jobs j
        ORDER BY j.$date_column DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Jobs Posted by Companies - Placement Management System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">

    <div class="card shadow p-4 mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3><i class="fas fa-briefcase text-info"></i> Jobs Posted by Companies</h3>
            <form method="post" class="m-0">
                <button type="submit" name="export_excel" class="btn btn-info">
                   ⬇️
                </button>
            </form>
        </div>
        <p>List of all jobs posted by companies.</p>
    </div>

    <div class="card shadow p-4">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Job ID</th>
                    <th>Job Title</th>
                    <th>Company Name</th>
                    <th>Posted At</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['job_id'] ?></td>
                            <td><?= htmlspecialchars($row['job_title']) ?></td>
                            <td><?= htmlspecialchars($row['company_name']) ?></td>
                            <td><?= $row[$date_column] ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="4" class="text-center">No jobs posted yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="text-center mt-3 mb-5">
        <a href="company_reports.php" class="btn btn-primary">Back</a>
    </div>

</div>
</body>
</html>

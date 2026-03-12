<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "placement_system");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Export to Excel
if (isset($_POST['export_excel'])) {
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=students_applied_jobs.xls");

    echo "Job Title\tCompany Name\tStudent Name\tEmail\tPhone\tResume\tApplied Date\n";

    $sql = "SELECT j.job_title, j.company_name, s.name AS student_name, s.email, sp.phone, sp.resume_path, ja.applied_date
            FROM job_applications ja
            JOIN jobs j ON ja.job_id = j.id
            JOIN students s ON ja.student_id = s.id
            LEFT JOIN student_profiles sp ON sp.student_id = s.id
            ORDER BY ja.applied_date DESC";

    $result = $conn->query($sql);

    while ($row = $result->fetch_assoc()) {
        $resume = (!empty($row['resume_path']) && file_exists($row['resume_path'])) ? $row['resume_path'] : 'N/A';
        echo $row['job_title'] . "\t" . $row['company_name'] . "\t" . $row['student_name'] . "\t" . $row['email'] . "\t" . ($row['phone'] ?? 'N/A') . "\t" . $resume . "\t" . $row['applied_date'] . "\n";
    }
    exit();
}

// Fetch data for display
$sql = "SELECT j.job_title, j.company_name, s.name AS student_name, s.email, sp.phone, sp.resume_path, ja.applied_date
        FROM job_applications ja
        JOIN jobs j ON ja.job_id = j.id
        JOIN students s ON ja.student_id = s.id
        LEFT JOIN student_profiles sp ON sp.student_id = s.id
        ORDER BY ja.applied_date DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Students Applied to Jobs - Placement Management System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">

    <div class="card shadow p-4 mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3><i class="fas fa-users text-danger"></i> Students Applied to Company Jobs</h3>
            <form method="post" class="m-0">
                <button type="submit" name="export_excel" class="btn btn-danger">
                    ⬇️
                </button>
            </form>
        </div>
        <p>List of all students who applied to jobs posted by companies.</p>
    </div>

    <div class="card shadow p-4">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Job Title</th>
                    <th>Company Name</th>
                    <th>Student Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Resume</th>
                    <th>Applied Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['job_title']) ?></td>
                            <td><?= htmlspecialchars($row['company_name']) ?></td>
                            <td><?= htmlspecialchars($row['student_name']) ?></td>
                            <td><?= htmlspecialchars($row['email']) ?></td>
                            <td><?= htmlspecialchars($row['phone'] ?? 'N/A') ?></td>
                            <td>
                                <?php
                                if (!empty($row['resume_path']) && file_exists($row['resume_path'])) {
                                    echo '<a href="' . $row['resume_path'] . '" target="_blank">View Resume</a>';
                                } else {
                                    echo '<span class="text-muted">N/A</span>';
                                }
                                ?>
                            </td>
                            <td><?= $row['applied_date'] ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="7" class="text-center">No student applications found.</td></tr>
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

<?php $conn->close(); ?>

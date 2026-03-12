<?php
session_start();
$conn = new mysqli("localhost", "root", "", "placement_system");

if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// -------------------------------------------------
// DOWNLOAD CSV FOR STUDENTS APPLIED TO JOBS
// -------------------------------------------------
if (isset($_GET['download']) && $_GET['download'] == 'excel') {

    $sql = "SELECT ja.applied_date, s.name AS student_name, j.job_title 
            FROM job_applications ja
            JOIN students s ON ja.student_id = s.id
            JOIN jobs j ON ja.job_id = j.id
            ORDER BY ja.applied_date DESC";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {

        $filename = "students_applied_jobs_" . date('Y-m-d') . ".csv";

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');

        // Column headings
        fputcsv($output, array('Student Name', 'Job Title', 'Applied Date'));

        while ($row = $result->fetch_assoc()) {
            fputcsv($output, array(
                $row['student_name'],
                $row['job_title'],
                $row['applied_date']
            ));
        }

        fclose($output);
        exit();
    } else {
        echo "No application records found!";
        exit();
    }
}

// NORMAL PAGE LOAD QUERY
$sql = "SELECT ja.*, s.name AS student_name, j.job_title 
        FROM job_applications ja
        JOIN students s ON ja.student_id = s.id
        JOIN jobs j ON ja.job_id = j.id
        ORDER BY ja.applied_date DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Students Applied to Jobs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
<div class="container mt-5">

    <!-- HEADER + DOWNLOAD BUTTON -->
    <div class="card p-4 shadow d-flex flex-row justify-content-between align-items-center mb-4">
        <h3 class="text-info m-0">Students Applied to Jobs</h3>

        <!-- DOWNLOAD BUTTON -->
        <a href="?download=excel" class="btn btn-success btn-sm">
            ⬇️
        </a>
    </div>

    <div class="card p-4 shadow">
        <table class="table table-bordered mt-3">
            <tr class="table-dark">
                <th>Student</th>
                <th>Job Title</th>
                <th>Date Applied</th>
            </tr>

            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['student_name']) ?></td>
                    <td><?= htmlspecialchars($row['job_title']) ?></td>
                    <td><?= $row['applied_date'] ?></td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="3" class="text-center text-muted">No job applications found.</td></tr>
            <?php endif; ?>
        </table>
    </div>

    <div class="text-center mt-3">
        <a href="student_reports.php" class="btn btn-secondary">Back</a>
    </div>

</div>
</body>
</html>

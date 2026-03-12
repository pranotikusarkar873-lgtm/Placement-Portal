<?php
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "placement_system");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// --------------------------------------------------
// DOWNLOAD APPROVED STUDENTS CSV
// --------------------------------------------------
if (isset($_GET['download']) && $_GET['download'] == 'excel') {

    $sql = "
        SELECT DISTINCT s.id, s.name, s.email
        FROM students s
        INNER JOIN job_applications j ON s.id = j.student_id
    ";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {

        $filename = "approved_students_" . date('Y-m-d') . ".csv";

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');

        // Column headings
        fputcsv($output, array('Student ID', 'Student Name', 'Email'));

        while ($row = $result->fetch_assoc()) {
            fputcsv($output, array(
                $row['id'],
                $row['name'],
                $row['email']
            ));
        }

        fclose($output);
        exit();
    } else {
        echo "No approved student records found!";
        exit();
    }
}

// Normal page load
$sql = "
    SELECT DISTINCT s.id, s.name, s.email
    FROM students s
    INNER JOIN job_applications j ON s.id = j.student_id
";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Approved Students</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
<div class="container mt-5">

    <!-- Header with Download Button -->
    <div class="card p-4 shadow mb-4 d-flex flex-row justify-content-between align-items-center">
        <h3 class="text-primary m-0">Approved Students</h3>

        <!-- DOWNLOAD BUTTON -->
        <a href="?download=excel" class="btn btn-success btn-sm">
            ⬇️ 
        </a>
    </div>

    <div class="card p-4 shadow">
        <table class="table table-bordered table-striped">
            <tr class="table-dark">
                <th>ID</th>
                <th>Student Name</th>
                <th>Email</th>
            </tr>

            <?php if ($result && $result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="3" class="text-center text-muted">No approved students.</td></tr>
            <?php endif; ?>
        </table>
    </div>

    <div class="text-center mt-3">
        <a href="student_reports.php" class="btn btn-secondary">Back</a>
    </div>

</div>
</body>
</html>

<?php
session_start();
$conn = new mysqli("localhost", "root", "", "placement_system");

if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// --------------------------------------------------
// DOWNLOAD CSV FOR PENDING STUDENTS
// --------------------------------------------------
if (isset($_GET['download']) && $_GET['download'] == 'excel') {

    $sql = "
        SELECT s.id, s.name, s.email
        FROM students s
        LEFT JOIN job_applications j ON s.id = j.student_id
        WHERE j.student_id IS NULL
    ";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {

        $filename = "pending_students_" . date('Y-m-d') . ".csv";

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
        echo "No pending student records found!";
        exit();
    }
}

// NORMAL PAGE LOAD QUERY
$sql = "
    SELECT s.id, s.name, s.email
    FROM students s
    LEFT JOIN job_applications j ON s.id = j.student_id
    WHERE j.student_id IS NULL
";

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Pending Students</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
<div class="container mt-5">

    <!-- Header + Download -->
    <div class="card p-4 shadow d-flex flex-row justify-content-between align-items-center mb-4">
        <h3 class="text-warning m-0">Pending Students</h3>

        <!-- DOWNLOAD BUTTON -->
        <a href="?download=excel" class="btn btn-success btn-sm">
            ⬇️
        </a>
    </div>

    <div class="card p-4 shadow">
        <table class="table table-bordered table-striped">
            <tr class="table-dark">
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
            </tr>

            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="3" class="text-center text-muted">No pending students.</td></tr>
            <?php endif; ?>
        </table>
    </div>

    <div class="text-center mt-3">
        <a href="student_reports.php" class="btn btn-secondary">Back</a>
    </div>

</div>
</body>
</html>

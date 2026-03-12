<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "placement_system");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// ----------------------------------------------
// DOWNLOAD EXCEL (CSV) for All Students
// ----------------------------------------------
if (isset($_GET['download']) && $_GET['download'] == 'excel') {

    $sql = "SELECT id, name, email FROM students ORDER BY id ASC";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {

        $filename = "all_students_report_" . date('Y-m-d') . ".csv";

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
        echo "No student records found!";
        exit();
    }
}

// Normal page load
$sql = "SELECT id, name, email FROM students ORDER BY id ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>All Students Report</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
<div class="container mt-5">

    <!-- Header with Download Button -->
    <div class="card p-4 shadow mb-4 d-flex justify-content-between align-items-center flex-row">
        <h3 class="m-0">All Registered Students</h3>

        <!-- Download button -->
        <a href="?download=excel" class="btn btn-success btn-sm">
            ⬇️
        </a>
    </div>

    <div class="card p-4 shadow">
        <table class="table table-bordered">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
            </tr>

            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id']; ?></td>
                <td><?= htmlspecialchars($row['name']); ?></td>
                <td><?= htmlspecialchars($row['email']); ?></td>
            </tr>
            <?php endwhile; ?>

        </table>
    </div>

    <div class="text-center mt-3">
        <a href="student_reports.php" class="btn btn-secondary">Back</a>
    </div>

</div>
</body>
</html>

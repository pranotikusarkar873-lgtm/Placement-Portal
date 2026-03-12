<?php
session_start();
$conn = new mysqli("localhost", "root", "", "placement_system");

// ---------------------------------------------
// DOWNLOAD EXCEL
// ---------------------------------------------
if (isset($_GET['download']) && $_GET['download'] == 'excel') {

    $sql = "SELECT s.name AS student_name, n.message, n.created_at
            FROM notifications n
            JOIN students s ON n.student_id = s.id
            ORDER BY n.created_at DESC";

    $result = $conn->query($sql);

    $filename = "student_notifications_report_" . date('Y-m-d') . ".csv";

    header("Content-Type: text/csv");
    header("Content-Disposition: attachment; filename=\"$filename\"");

    $output = fopen("php://output", "w");

    fputcsv($output, ["Student Name", "Message", "Date"]);

    while ($row = $result->fetch_assoc()) {
        fputcsv($output, [
            $row["student_name"],
            $row["message"],
            $row["created_at"]
        ]);
    }

    fclose($output);
    exit();
}

// ---------------------------------------------
// FETCH DATA FOR NORMAL PAGE VIEW
// ---------------------------------------------
$sql = "SELECT s.name AS student_name, n.message, n.created_at
        FROM notifications n
        JOIN students s ON n.student_id = s.id
        ORDER BY n.created_at DESC";

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Student Notifications Report</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <!-- FontAwesome (for bell icon) -->
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</head>

<body class="bg-light">

<div class="container mt-5">

    <!-- Header Card with Right-Side Download Button -->
    <div class="card p-4 shadow mb-4 d-flex flex-row justify-content-between align-items-center">
        <h3 class="text-warning m-0"><i class="fas fa-bell"></i> Student Notifications Report</h3>

        <!-- DOWNLOAD BUTTON -->
         <a href="?download=excel" class="btn btn-success btn-sm">
            ⬇️ 
        </a>
    </div>

    <!-- Report Table -->
    <div class="card p-4 shadow">
        <table class="table table-bordered table-striped">
            <tr class="table-dark">
                <th>Student Name</th>
                <th>Message</th>
                <th>Date</th>
            </tr>

            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['student_name']) ?></td>
                <td><?= htmlspecialchars($row['message']) ?></td>
                <td><?= htmlspecialchars($row['created_at']) ?></td>
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

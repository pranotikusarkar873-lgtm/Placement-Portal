<?php
session_start();
$conn = new mysqli("localhost", "root", "", "placement_system");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle date filter
$from_date = $_POST['from_date'] ?? '';
$to_date = $_POST['to_date'] ?? '';

// Base SQL
$sql = "SELECT id, name, email, phone, resume, registered_at FROM students";

// Add filter condition
$where = "";
if (!empty($from_date) && !empty($to_date)) {
    $where = " WHERE DATE(registered_at) BETWEEN '$from_date' AND '$to_date'";
}

$sql .= $where . " ORDER BY registered_at DESC";

$result = $conn->query($sql);

// -------------------- DOWNLOAD CSV --------------------
if (isset($_GET['download']) && $_GET['download'] === 'excel') {

    $download_sql = "SELECT id, name, email, phone, resume, registered_at FROM students" . $where . " ORDER BY registered_at DESC";
    $download_result = $conn->query($download_sql);

    $filename = "datewise_student_report_" . date('Y-m-d') . ".csv";

    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '"');

    $output = fopen('php://output', 'w');

    // Column headers
    fputcsv($output, ['ID', 'Name', 'Email', 'Phone', 'Resume', 'Registered Date']);

    while ($row = $download_result->fetch_assoc()) {
        fputcsv($output, [
            $row['id'],
            $row['name'],
            $row['email'],
            $row['phone'],
            $row['resume'],
            $row['registered_at']
        ]);
    }

    fclose($output);
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Date-wise Student Report</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .download-btn {
            float: right;
        }
    </style>
</head>
<body>
<div class="container mt-5">

    <div class="card p-4 shadow">
        <h3 class="mb-3">Date-wise Student Report</h3>

       <form method="post" class="row g-3 mb-4 align-items-end">

    <div class="col-md-3">
        <label>From Date:</label>
        <input type="date" name="from_date" value="<?= htmlspecialchars($from_date); ?>" class="form-control" required>
    </div>

    <div class="col-md-3">
        <label>To Date:</label>
        <input type="date" name="to_date" value="<?= htmlspecialchars($to_date); ?>" class="form-control" required>
    </div>

    <!-- Small Filter Button -->
    <div class="col-md-2">
        <button type="submit" class="btn btn-primary w-100 btn-sm">Filter</button>
    </div>

    <!-- Download Button to Right of Filter -->
    <div class="col-md-2">
        <a href="?download=excel&from_date=<?= $from_date ?>&to_date=<?= $to_date ?>" 
           class="btn btn-success w-100 btn-sm">
            ⬇️ 
        </a>
    </div>

</form>


       

        <table class="table table-bordered table-striped">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Resume</th>
                <th>Registered At</th>
            </tr>

            <?php if($result && $result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id']; ?></td>
                    <td><?= htmlspecialchars($row['name']); ?></td>
                    <td><?= htmlspecialchars($row['email']); ?></td>
                    <td><?= htmlspecialchars($row['phone']); ?></td>
                    <td><?= htmlspecialchars($row['resume']); ?></td>
                    <td><?= $row['registered_at']; ?></td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="6" class="text-center">No students found for selected date range.</td></tr>
            <?php endif; ?>
        </table>

    </div>

     <div class="text-center mt-3 mb-5">
        <a href="student_reports.php" class="btn btn-primary">Back</a>
    </div>
   
</div>
</body>
</html>

<?php $conn->close(); ?>

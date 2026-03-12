<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "placement_system");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$start = $_GET['start_date'] ?? '';
$end = $_GET['end_date'] ?? '';

if ($start && $end) {
    $sql = "SELECT * FROM companies WHERE DATE(registered_at) BETWEEN '$start' AND '$end' ORDER BY registered_at DESC";
} else {
    $sql = "SELECT * FROM companies ORDER BY registered_at DESC";
}
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Placement Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5 p-4 bg-white shadow rounded">
    <h3 class="text-center mb-4">Company Registration Report</h3>

    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-4">
            <label>Start Date</label>
            <input type="date" name="start_date" class="form-control" value="<?= htmlspecialchars($start); ?>" required>
        </div>
        <div class="col-md-4">
            <label>End Date</label>
            <input type="date" name="end_date" class="form-control" value="<?= htmlspecialchars($end); ?>" required>
        </div>
        <div class="col-md-4 align-self-end">
            <button type="submit" class="btn btn-primary w-100">View Report</button>
        </div>
    </form>

    <?php if ($result && $result->num_rows > 0): ?>
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Company Name</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Registered At</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['name']); ?></td>
                    <td><?= htmlspecialchars($row['email']); ?></td>
                    <td><?= htmlspecialchars(ucfirst($row['status'])); ?></td>
                    <td><?= date("d-M-Y H:i", strtotime($row['registered_at'])); ?></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="text-center">No companies found in this date range.</p>
    <?php endif; ?>

    <div class="text-center">
        <a href="admin_.php" class="btn btn-primary back-btn"></i> Back to Dashboard</a>
    </div>
</div>

</body>
</html>

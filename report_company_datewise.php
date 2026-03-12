<?php
session_start();
$conn = new mysqli("localhost", "root", "", "placement_system");

$start = $_GET['start'] ?? "";
$end = $_GET['end'] ?? "";

$sql = "SELECT id, company_name, email, created_at 
        FROM companies 
        WHERE 1";

if (!empty($start) && !empty($end)) {
    $sql .= " AND DATE(created_at) BETWEEN '$start' AND '$end'";
}

$sql .= " ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Date-wise Company Report</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-4">
    <div class="card p-4 shadow">
        <h3>Date-wise Company Report</h3>

        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-4">
                <label class="form-label">Start Date</label>
                <input type="date" name="start" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">End Date</label>
                <input type="date" name="end" class="form-control" required>
            </div>
            <div class="col-md-4 mt-4">
                <button class="btn btn-primary">Filter</button>
            </div>
        </form>

        <table class="table table-striped">
            <tr>
                <th>ID</th>
                <th>Company Name</th>
                <th>Email</th>
                <th>Registered On</th>
            </tr>

            <?php while($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?= $row['id']; ?></td>
                    <td><?= $row['company_name']; ?></td>
                    <td><?= $row['email']; ?></td>
                    <td><?= $row['created_at']; ?></td>
                </tr>
            <?php } ?>
        </table>
    </div>
</div>

</body>
</html>

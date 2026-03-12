<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin.php");
    exit();
}

// Database connection
$conn = new mysqli("localhost", "root", "", "placement_system");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ======================================================
// DOWNLOAD CSV
// ======================================================
if (isset($_GET['download']) && $_GET['download'] == "excel") {

    header("Content-Type: text/csv");
    header("Content-Disposition: attachment; filename=date_wise_company_report_" . date('Y-m-d') . ".csv");

    $output = fopen("php://output", "w");
    fputcsv($output, ["ID", "Name", "Email", "Status", "Registered At"]);

    if (!empty($_GET['start']) && !empty($_GET['end'])) {
        $start = $_GET['start'];
        $end = $_GET['end'];
        $sql = "SELECT * FROM companies WHERE DATE(registered_at) BETWEEN '$start' AND '$end' ORDER BY id DESC;";
    } else {
        $sql = "SELECT * FROM companies ORDER BY id DESC;";
    }

    $download_result = $conn->query($sql);

    while ($row = $download_result->fetch_assoc()) {
        fputcsv($output, [
            $row['id'],
            $row['name'],
            $row['email'],
            $row['status'],
            $row['registered_at']
        ]);
    }
    fclose($output);
    exit();
}

// ======================================================
// FILTER SYSTEM
// ======================================================
$filtered_companies = [];
$start_date = '';
$end_date = '';

if (isset($_POST['filter'])) {
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    if ($start_date && $end_date) {
        $stmt = $conn->prepare("SELECT * FROM companies WHERE DATE(registered_at) BETWEEN ? AND ? ORDER BY id DESC");
        $stmt->bind_param("ss", $start_date, $end_date);
        $stmt->execute();
        $result = $stmt->get_result();
        $filtered_companies = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
    }
}

// Fetch all companies
$all_companies_result = $conn->query("SELECT * FROM companies ORDER BY id DESC");
$all_companies = $all_companies_result->fetch_all(MYSQLI_ASSOC);

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Date-wise Company Report</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .btn-sm {
            padding: 5px 10px;
            font-size: 13px;
        }
        .header-box {
            margin-top: 30px;
            margin-bottom: 10px;
        }
    </style>

</head>
<body class="bg-light">

<div class="container header-box d-flex justify-content-between align-items-center">
    <h3 class="m-0">Date-wise Company Report</h3>

  
</div>

<div class="container">

    <!-- Filter Form -->
    <div class="card shadow p-4 mb-4">
        <form method="post" class="row g-3 align-items-end">

            <div class="col-md-4">
                <label class="form-label">Start Date</label>
                <input type="date" name="start_date" class="form-control"
                       value="<?= htmlspecialchars($start_date); ?>" required>
            </div>

            <div class="col-md-4">
                <label class="form-label">End Date</label>
                <input type="date" name="end_date" class="form-control"
                       value="<?= htmlspecialchars($end_date); ?>" required>
            </div>

            <!-- Small Filter Button -->
            <div class="col-md-2">
                <button type="submit" name="filter" class="btn btn-primary btn-sm w-100">Filter</button>
            </div>

            <!-- Small Download Button Right of Filter -->
            <div class="col-md-2">
                <a href="?download=excel&start=<?= $start_date ?>&end=<?= $end_date ?>" 
                   class="btn btn-success btn-sm w-100">
                    ⬇️
                </a>
            </div>

        </form>
    </div>

    <!-- Filtered list -->
    <?php if (!empty($filtered_companies)): ?>
        <div class="card shadow p-4 mb-4">
            <h5>Filtered Companies (<?= $start_date ?> to <?= $end_date ?>)</h5>
            <table class="table table-bordered table-striped mt-3">
                <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Registered At</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($filtered_companies as $c): ?>
                    <tr>
                        <td><?= $c['id']; ?></td>
                        <td><?= htmlspecialchars($c['name']); ?></td>
                        <td><?= htmlspecialchars($c['email']); ?></td>
                        <td><?= ucfirst($c['status']); ?></td>
                        <td><?= $c['registered_at']; ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php elseif (isset($_POST['filter'])): ?>
        <div class="alert alert-info mb-4">No companies found in this date range.</div>
    <?php endif; ?>

    <!-- All companies -->
    <div class="card shadow p-4">
        <h5>All Companies</h5>
        <table class="table table-bordered table-striped mt-3">
            <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Status</th>
                <th>Registered At</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($all_companies as $c): ?>
                <tr>
                    <td><?= $c['id']; ?></td>
                    <td><?= htmlspecialchars($c['name']); ?></td>
                    <td><?= htmlspecialchars($c['email']); ?></td>
                    <td><?= ucfirst($c['status']); ?></td>
                    <td><?= $c['registered_at']; ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    
  <div class="text-center mt-3 mb-5">
        <a href="company_reports.php" class="btn btn-primary">Back</a>
    </div>
</div>

</body>
</html>

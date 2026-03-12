<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Placement Portal</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">

    <div class="card shadow p-4 mb-4">
        <h3><i class="fas fa-chart-line text-primary"></i> All Reports</h3>
        <p>Select a report category to view detailed reports.</p>
    </div>

    <!-- Report List -->
    <div class="card shadow p-4">

        <!-- Placement Reports -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="m-0"><i class="fas fa-chart-pie text-primary"></i> Placement Reports</h5>
            <a href="placement_reports.php" class="btn btn-primary">View</a>
        </div>
        <hr>

        <!-- Student Reports -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="m-0"><i class="fas fa-users text-primary"></i> Student Reports</h5>
            <a href="student_reports.php" class="btn btn-primary">View</a>
        </div>
        <hr>

        <!-- Company Reports -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="m-0"><i class="fas fa-building text-success"></i> Company Reports</h5>
            <a href="company_reports.php" class="btn btn-success">View</a>
        </div>
        <hr>

        <!-- Feedback Reports -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="m-0"><i class="fas fa-comments text-info"></i> Feedback Reports</h5>
            <a href="feedback_report.php" class="btn btn-info">View</a>
        </div>

    </div>

</div>

<div class="text-center mt-3">
    <a href="admin_dashboard.php" class="btn btn-primary">Back to Dashboard</a>
</div>

</body>
</html>

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
    <title>Placement Reports - Placement Management System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">

    <!-- Header -->
    <div class="card shadow p-4 mb-4">
        <h3><i class="fas fa-chart-line text-primary"></i> Placement Reports</h3>
        <p>Select the report you want to view.</p>
    </div>

    <!-- Reports List -->
    <div class="card shadow p-4">

        <!-- 1. Placed Students Report -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="m-0"><i class="fas fa-user-check text-success"></i> Placed Students Report</h5>
            <a href="placed_students_report.php" class="btn btn-success">View</a>
        </div>
        <hr>

        <!-- 2. Company-wise Placed Students -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="m-0"><i class="fas fa-building text-primary"></i> Company-wise Placed Students</h5>
            <a href="company_wise_placements.php" class="btn btn-primary">View</a>
        </div>
        <hr>


        <!-- 4. Year-wise Comparison Graph -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="m-0"><i class="fas fa-chart-bar text-info"></i> Year-wise Comparison Graph</h5>
            <a href="year_wise_comparison_chart.php" class="btn btn-info">View</a>
        </div>

    </div>

</div>

<!-- Back Button -->
<div class="text-center mt-3 mb-5">
    <a href="admin_reports.php" class="btn btn-primary">Back to Dashboard</a>
</div>

</body>
</html>

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
    <title>Company Reports - Placement Management System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-light">
<div class="container mt-5">

    <div class="card shadow p-4 mb-4">
        <h3><i class="fas fa-building text-success"></i> Company Reports</h3>
        <p>Select a company-related report to view details.</p>
    </div>

    <div class="card shadow p-4">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="m-0"><i class="fas fa-calendar text-primary"></i> Date wise Report</h5>
            <a href="view_datewise.php" class="btn btn-primary">View</a>
        </div>
        <hr>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="m-0"><i class="fas fa-list text-primary"></i> All Companies Registered</h5>
            <a href="view_all_companies.php" class="btn btn-primary">View</a>
        </div>
        <hr>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="m-0"><i class="fas fa-check text-success"></i> Approved Companies</h5>
            <a href="view_approved_companies.php" class="btn btn-success">View</a>
        </div>
        <hr>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="m-0"><i class="fas fa-hourglass-half text-warning"></i> Pending Companies</h5>
            <a href="view_pending_companies.php" class="btn btn-warning">View</a>
        </div>
        <hr>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="m-0"><i class="fas fa-briefcase text-info"></i> Jobs Posted by Companies</h5>
            <a href="view_company_jobs.php" class="btn btn-info">View</a>
        </div>
        <hr>

       
 <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="m-0"><i class="fas fa-bell text-danger"></i> Students Applied to Company Jobs</h5>
            <a href="view_students_applied.php" class="btn btn-danger">View</a>
        </div>
    </div>

</div>

<div class="text-center mt-3 mb-5">
    <a href="admin_reports.php" class="btn btn-primary">Back to Dashboard</a>
</div>

</body>
</html>

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
    <title>Student Reports - Placement Management System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">

    <!-- Header -->
    <div class="card shadow p-4 mb-4">
        <h3><i class="fas fa-file-alt text-primary"></i> Student Reports</h3>
        <p>Select the report you want to view.</p>
    </div>

    <!-- Reports List -->
    <div class="card shadow p-4">

        <!-- 1. Date Wise Report -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="m-0">
                <i class="fas fa-calendar-alt text-danger"></i> Date Wise Report
            </h5>
            <a href="report_datewise.php" class="btn btn-danger">View</a>
        </div>
        <hr>

        <!-- 2. All Students Report -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="m-0"><i class="fas fa-users text-primary"></i> All Students</h5>
            <a href="report_all_students.php" class="btn btn-primary">View</a>
        </div>
        <hr>

        <!-- 3. Approved Students -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="m-0"><i class="fas fa-check-circle text-success"></i> Approved Students</h5>
            <a href="report_approved_students.php" class="btn btn-success">View</a>
        </div>
        <hr>

        <!-- 4. Pending Students -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="m-0"><i class="fas fa-hourglass-half text-warning"></i> Pending Students</h5>
            <a href="report_pending_students.php" class="btn btn-warning">View</a>
        </div>
        <hr>

        <!-- 5. Students Applied to Jobs -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="m-0"><i class="fas fa-user-graduate text-secondary"></i> Students Applied for Jobs</h5>
            <a href="report_students_applied.php" class="btn btn-secondary">View</a>
        </div>
        <hr>

        <!-- 6. Student Resume Uploaded -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="m-0"><i class="fas fa-file-upload text-info"></i> Students with Uploaded Resume</h5>
            <a href="report_resume_uploaded.php" class="btn btn-info">View</a>
        </div>
        <hr>

        <!-- 7. Notification Report -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="m-0"><i class="fas fa-bell text-danger"></i> Student Notifications</h5>
            <a href="report_notifications.php" class="btn btn-danger">View</a>
        </div>

    </div>

</div>

<!-- Back Button -->
<div class="text-center mt-3 mb-5">
    <a href="admin_reports.php" class="btn btn-primary">Back to Dashboard</a>
</div>

</body>
</html>

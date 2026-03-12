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
    <title>Feedback Reports - Placement Management System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">

    <style>
        /* Card shadow effect for consistent design */
        .card-custom {
            box-shadow: 0 0 12px rgba(0,0,0,0.1);
            border-radius: 10px;
        }

        .back-btn {
            background-color: #007bff;
            color: #fff;
            padding: 8px 20px;
            border-radius: 5px;
            text-decoration: none;
            transition: 0.3s;
        }

        .back-btn:hover {
            background-color: #0056b3;
            color: #fff;
        }
    </style>
</head>

<body class="bg-light">

<div class="container mt-5">

    <!-- Header -->
    <div class="card card-custom shadow p-4 mb-4">
        <h3><i class="fas fa-comments text-primary"></i> Feedback Reports</h3>
        <p>Select the report you want to view.</p>
    </div>

    <!-- Reports List -->
    <div class="card card-custom shadow p-4">

        <!-- 1. Student Feedback Report -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="m-0"><i class="fas fa-user-graduate text-primary"></i> Student Feedback Report</h5>
            <a href="feedback_student_report.php" class="btn btn-primary">View</a>
        </div>
        <hr>

        <!-- 2. Company Feedback Report -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="m-0"><i class="fas fa-building text-success"></i> Company Feedback Report</h5>
            <a href="feedback_company_report.php" class="btn btn-success">View</a>
        </div>
        <hr>

        <!-- 3. Admin Action History -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="m-0"><i class="fas fa-history text-warning"></i> Admin Action History</h5>
            <a href="feedback_admin_action_report.php" class="btn btn-warning">View</a>
        </div>

    </div>

</div>

<!-- Back Button -->
<div class="text-center mt-3 mb-5">
    <a href="admin_reports.php" class="back-btn"> Back to Dashboard</a>
</div>

</body>
</html>

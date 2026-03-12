<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin.php");
    exit();
}
if (isset($_GET['logout'])) {
    session_destroy();
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
    
    <!-- Bootstrap & FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

    <style>
        .dashboard-container {
            max-width: 1100px;
            margin: 40px auto;
        }
        .card {
            border-radius: 12px;
        }
    </style>
</head>

<body class="bg-light">

<!-- Navbar Start -->
<div class="container-fluid p-0">
    <nav class="navbar navbar-expand-lg bg-white navbar-light px-lg-5">
        <a href="index.php" class="navbar-brand ml-lg-3">
            <h3 class="text-uppercase text-primary"><i class="fa fa-book-reader mr-3"></i>Placement Portal</h3>
        </a>
        <div class="collapse navbar-collapse justify-content-between px-lg-3">
            <div class="navbar-nav mx-auto py-0">
                <a href="index.php" class="nav-item nav-link">Home</a>
                <a href="about.php" class="nav-item nav-link">About</a>
                <a href="admin.php" class="nav-item nav-link active">Admin</a>
                <a href="student_portal.php" class="nav-item nav-link">Student</a>
                <a href="company.php" class="nav-item nav-link">Companies</a>

                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Pages</a>
                    <div class="dropdown-menu m-0">
                        <a href="gallery.php" class="dropdown-item">Gallery</a>
                        <a href="department.php" class="dropdown-item">Department</a>
                        <a href="contact.php" class="dropdown-item">Contact Us</a>
                    </div>
                </div>
                <a href="feedback.php" class="nav-item nav-link">Feedback</a>
            </div>

            <?php if (!isset($_SESSION['admin_logged_in'])): ?>
                <a href="student_portal.php" class="btn btn-primary py-2 px-4 d-none d-lg-block">Login</a>
            <?php else: ?>
                <a href="?logout=true" class="btn btn-danger py-2 px-4 d-none d-lg-block">Logout</a>
            <?php endif; ?>
        </div>
    </nav>
</div>
<!-- Navbar End -->

<div class="container dashboard-container">
    <div class="card shadow p-4 mb-4">
        <h2 class="mb-3">Welcome, <?php echo $_SESSION['admin_name']; ?> (TPO)</h2>
        <p>You are logged in to the Placement Portal Admin Dashboard.</p>
    </div>

    <div class="row g-4">

        <!-- Manage Students -->
        <div class="col-md-6">
            <div class="card shadow p-4">
                <h5 class="card-title mb-3">
                    <i class="fas fa-user-graduate text-info"></i> Manage Students & Notifications
                </h5>
                <p>View all student profiles, filter by skills/job roles, and send notifications.</p>
                <a href="admin_student_list.php" class="btn btn-info">Manage Students</a>
            </div>
        </div>

        <!-- Pending Company Approvals -->
        <div class="col-md-6">
            <div class="card shadow p-4">
                <h5 class="card-title mb-3">
                    <i class="fas fa-building text-warning"></i> Pending Company Approvals
                </h5>
                <p>Approve new companies awaiting verification.</p>
                <a href="admin_pending_companies.php" class="btn btn-warning">Manage Companies</a>
            </div>
        </div>

        <!-- ============= REPORTS COMBINED BOX ============= -->
      <!-- REPORTS BOX (only one button) -->
<div class="col-md-6">
    <div class="card shadow p-4">
        <h5 class="card-title mb-3">
            <i class="fas fa-chart-line text-primary"></i> Reports
        </h5>
        <p>View Reports </p>
        <a href="admin_reports.php" class="btn btn-primary">View Reports</a>
    </div>
</div>


               

        <!-- Add Placed Student -->
        <div class="col-md-6">
            <div class="card shadow p-4">
                <h5 class="card-title mb-3">
                    <i class="fas fa-user-check text-success"></i> Add Placed Student
                </h5>
                <p>Add details of students who got placed to show in gallery.</p>
                <a href="add_placed_student.php" class="btn btn-success">Go to Add Form</a>
            </div>
        </div>

    </div>
</div>

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

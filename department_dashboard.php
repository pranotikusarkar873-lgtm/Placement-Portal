<?php
session_start();

// Handle logout
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("Location: department.php");
    exit();
}

// Check if the teacher is logged in
if (!isset($_SESSION['teacher_id'])) {
    header("Location: department.php");
    exit();
}

// Safely fetch session data
$teacher_name = isset($_SESSION['teacher_name']) ? $_SESSION['teacher_name'] : '';
$teacher_email = isset($_SESSION['teacher_email']) ? $_SESSION['teacher_email'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Department Dashboard - PMS</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    
    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500;600;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet"> 

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
</head>

<body>
    <!-- Topbar Start -->
    <div class="container-fluid bg-dark">
        <div class="row py-2 px-lg-5">
            <div class="col-lg-6 text-center text-lg-left mb-2 mb-lg-0">
                <div class="d-inline-flex align-items-center text-white">
                    <small><i class="fa fa-phone-alt mr-2"></i></small>
                    <small class="px-3">|</small>
                    <small><i class="fa fa-envelope mr-2"></i></small>
                </div>
            </div>
            <div class="col-lg-6 text-center text-lg-right">
                <div class="d-inline-flex align-items-center">
                    <a class="text-white px-2" href=""><i class="fab fa-facebook-f"></i></a>
                    <a class="text-white px-2" href=""><i class="fab fa-twitter"></i></a>
                    <a class="text-white px-2" href=""><i class="fab fa-linkedin-in"></i></a>
                    <a class="text-white px-2" href=""><i class="fab fa-instagram"></i></a>
                    <a class="text-white pl-2" href=""><i class="fab fa-youtube"></i></a>
                </div>
            </div>
        </div>
    </div>
    <!-- Topbar End -->

    <!-- Navbar Start -->
    <div class="container-fluid p-0">
        <nav class="navbar navbar-expand-lg bg-white navbar-light py-3 py-lg-0 px-lg-5">
            <a href="index.php" class="navbar-brand ml-lg-3">
                <h3 class="m-0 text-uppercase text-primary"><i class="fa fa-book-reader mr-3"></i>Placement Portal</h3>
            </a>
            <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-between px-lg-3" id="navbarCollapse">
                <div class="navbar-nav mx-auto py-0">
                    <a href="index.php" class="nav-item nav-link">Home</a>
                    <a href="about.php" class="nav-item nav-link">About</a>
                    <a href="admin.php" class="nav-item nav-link">Admin</a>
                    <a href="student_portal.php" class="nav-item nav-link">Student</a>
                    <a href="company.php" class="nav-item nav-link">Companies</a>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle active" data-toggle="dropdown">Pages</a>
                        <div class="dropdown-menu m-0">
                            <a href="gallery.php" class="dropdown-item">Gallery</a>
                            <a href="department.php" class="dropdown-item">Department</a>
                            <a href="contact.php" class="dropdown-item">Contact Us</a>
                        </div>
                    </div>
                    <a href="feedback.php" class="nav-item nav-link">Feedback</a>
                </div>
                <?php if (isset($_SESSION['teacher_id'])): ?>
                    <a href="?logout=true" class="btn btn-danger py-2 px-4" style="background-color: red; border-color: red;">Logout</a>
                <?php else: ?>
                    <a href="index.php" class="btn btn-primary py-2 px-4" style="background-color: red; border-color: red;">Logout</a>
                <?php endif; ?>
            </div>
        </nav>
    </div>
    <!-- Navbar End -->

    <!-- Welcome Topbar -->
    <div class="container-fluid bg-dark">
        <div class="row py-2 px-lg-5">
            <div class="col-lg-6 text-white">Welcome, <?php echo htmlspecialchars($teacher_name); ?>!</div>
            <div class="col-lg-6 text-lg-right"></div>
        </div>
    </div>
    <!-- End Welcome Topbar -->

    <!-- Dashboard Top Navbar -->
   
    <!-- End Dashboard Top Navbar -->

    <!-- Dashboard Content Start -->
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 bg-light shadow rounded p-4">
                <h3 class="mb-4 text-center text-primary">Teacher Dashboard</h3>
                <p><strong>Name:</strong> <?php echo htmlspecialchars($teacher_name); ?></p>
                
                <hr>
                <p>Welcome to your dashboard. You can add features like:</p>
                <ul>
                    <li>View student data</li>
                    <li>View job postings</li>
                    <li>Generate reports</li>
                    <li>Send feedback to admin</li>
                </ul>
            </div>
        </div>
    </div>
    <!-- Dashboard Content End -->

    <!-- JS Scripts -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="js/main.js"></script>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Placement Portal</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500;600;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/style.css" rel="stylesheet">

    <style>
        .page-header {
            background: linear-gradient(to right, #0052d4, #4364f7, #6fb1fc);
            padding: 100px 0;
            text-align: center;
            color: white;
        }
        .info-box {
            background: #fff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.08);
            margin-bottom: 20px;
        }
        .info-box i {
            font-size: 32px;
            color: #007bff;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>

    <!-- TOP BAR -->
    <div class="container-fluid bg-dark text-white py-2 px-lg-5">
        <div class="d-flex justify-content-between">
            <div>
                <small><i class="fa fa-phone-alt me-2"></i></small>
                <small class="px-3">|</small>
                <small><i class="fa fa-envelope me-2"></i></small>
            </div>
            <div>
                <a class="text-white px-2" href="#"><i class="fab fa-facebook-f"></i></a>
                <a class="text-white px-2" href="#"><i class="fab fa-twitter"></i></a>
                <a class="text-white px-2" href="#"><i class="fab fa-linkedin-in"></i></a>
                <a class="text-white px-2" href="#"><i class="fab fa-instagram"></i></a>
            </div>
        </div>
    </div>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white px-lg-5 py-2 shadow-sm">
        <a href="index.php" class="navbar-brand">
            <h4 class="m-0 text-primary"><i class="fa fa-book-reader me-2"></i>Placement Portal</h4>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-between" id="navbarCollapse">

            <!-- NAV LINKS -->
            <div class="navbar-nav mx-auto">
                <a href="index.php" class="nav-item nav-link">Home</a>
                <a href="about.php" class="nav-item nav-link active">About</a>
                <a href="admin.php" class="nav-item nav-link">Admin</a>
                <a href="student_portal.php" class="nav-item nav-link">Student</a>
                <a href="company.php" class="nav-item nav-link">Companies</a>

                <!-- Pages Dropdown -->
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button"
                       data-bs-toggle="dropdown" aria-expanded="false">
                        Pages
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="pagesDropdown">
                        <li><a class="dropdown-item" href="gallery.php">Gallery</a></li>
                        <li><a class="dropdown-item" href="department.php">Department</a></li>
                        <li><a class="dropdown-item" href="contact.php">Contact Us</a></li>
                    </ul>
                </div>

                <a href="feedback.php" class="nav-item nav-link">Feedback</a>
            </div>

            <!-- LOGIN DROPDOWN -->
            <div class="dropdown">
                <a class="btn btn-primary dropdown-toggle px-4" href="#" id="loginDropdown"
                   data-bs-toggle="dropdown" aria-expanded="false">
                    Login
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="loginDropdown">
                    <li><a class="dropdown-item" href="admin.php">Admin</a></li>
                    <li><a class="dropdown-item" href="student_portal.php">Student</a></li>
                    <li><a class="dropdown-item" href="company.php">Company</a></li>
                </ul>
            </div>

        </div>
    </nav>

    <!-- HEADER SECTION -->
    <div class="jumbotron jumbotron-fluid page-header position-relative overlay-bottom" style="margin-bottom: 40px;">
    <div class="container text-center py-3"> <!-- Reduced padding -->
        <h1 class="text-white display-4">About</h1> <!-- Smaller text -->
        <div class="d-inline-flex text-white mb-3">
            <p class="m-0 text-uppercase"><a class="text-white" href="index.php">Home</a></p>
            <i class="fa fa-angle-double-right pt-1 px-3"></i>
            <p class="m-0 text-uppercase">About</p>
        </div>
    </div>
</div>


    <!-- ABOUT CONTENT -->
    <div class="container about-section py-5">

        <div class="info-box">
            <h3><i class="fa fa-university"></i> About Our Placement Portal</h3>
            <p>
                The Placement Management System is designed to streamline all placement activities for students,
                companies, and the Training & Placement Office. It provides an efficient online platform for job postings,
                student applications, company shortlisting, and placement records.
            </p>
        </div>

        <div class="info-box">
            <h3><i class="fa fa-bullseye"></i> Our Mission</h3>
            <p>
                To bridge the gap between students and industry by providing an organized and effective placement system
                that enhances opportunities and supports students’ career growth.
            </p>
        </div>

        <div class="info-box">
            <h3><i class="fa fa-eye"></i> Our Vision</h3>
            <p>
                To build a future where every student has access to the best career opportunities through transparent,
                efficient, and technology-driven placement support.
            </p>
        </div>

        <div class="info-box">
            <h3><i class="fas fa-chalkboard-teacher"></i> Training & Placement Cell</h3>
            <p>
                The TPO team plays a crucial role in connecting students with recruiters. This portal maintains company
                profiles, job openings, student applications, selection lists, and placement statistics.
            </p>
        </div>

        <div class="info-box">
            <h3><i class="fa fa-user-graduate"></i> Why Students Love This Portal?</h3>
            <ul>
                <li>Easy registration and profile management.</li>
                <li>Instant job notifications.</li>
                <li>One-click job application.</li>
                <li>Live application & placement tracking.</li>
            </ul>
        </div>

    </div>

    <!-- FOOTER -->
    <footer class="bg-dark text-white text-center py-3">
        <p class="mb-0">Placement Portal</p>
    </footer>

    <!-- BOOTSTRAP JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

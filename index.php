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
        .hero-section {
            background: linear-gradient(to right, #0052d4, #4364f7, #6fb1fc);
            color: white;
            text-align: center;
            padding: 100px 20px;
        }
        .hero-section h1 {
            font-size: 3rem;
        }
        .hero-section p {
            font-size: 1.2rem;
            margin-top: 10px;
        }

        /* Proper Bootstrap dropdown fix */
        .dropdown-menu {
            margin-top: 10px !important;
        }

        .info-box {
            background-color: #f9f9f9;
            border-left: 5px solid #007bff;
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: 0 0 5px rgba(0,0,0,0.1);
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
            <div class="navbar-nav mx-auto">
                <a href="index.php" class="nav-item nav-link active">Home</a>
                <a href="about.php" class="nav-item nav-link">About</a>
                <a href="admin.php" class="nav-item nav-link">Admin</a>
                <a href="student_portal.php" class="nav-item nav-link">Student</a>
                <a href="company.php" class="nav-item nav-link">Companies</a>

                <!-- PAGES DROPDOWN -->
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

    <!-- HERO SECTION -->
    <section class="hero-section">
        <h1>Welcome to the Placement Portal</h1>
        <p id="quote-text">Empowering Students • Connecting Companies • Driving Careers</p>
    </section>

    <script>
        const quotes = [
            "Dream Big. Work Hard. Get Placed.",
            "Success is not final; failure is not fatal — keep going.",
            "Your future starts today. Take the first step.",
            "Empowering Students • Connecting Companies • Driving Careers."
        ];
        let q = 0;
        setInterval(() => {
            document.getElementById("quote-text").textContent = quotes[q];
            q = (q + 1) % quotes.length;
        }, 4000);
    </script>
<!-- ABOUT SECTION -->
<section class="container py-5">
    <div class="row align-items-center">
        <div class="col-md-6">
            <img src="img/11-placementportal.jpg" alt="About" class="img-fluid rounded shadow">
        </div>
        <div class="col-md-6">
            <h2 class="text-primary mb-3">About Our Portal</h2>
            <p>Our Placement Management System bridges the gap between students and recruiters...</p>
            <ul>
                <li>🎓 Create & Manage Student Profiles</li>
                <li>🏢 Company Job Listings & Shortlisting</li>
                <li>📊 Admin Dashboard & Reports</li>
            </ul>
        </div>
    </div>
</section>



    <!-- ACHIEVEMENTS -->
    <section class="section-bg py-5">
        <div class="container text-center">
            <h2 class="text-primary mb-4">Our Achievements</h2>
            <div class="row">
                <div class="col-md-3 mb-4">
                    <div class="counter-box">
                        <h2>120</h2>
                        <p>Companies</p>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="counter-box">
                        <h2>250</h2>
                        <p>Job Offers</p>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="counter-box">
                        <h2>85</h2>
                        <p>Placement Drives</p>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="counter-box">
                        <h2>123</h2>
                        <p>Students</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- WHY CHOOSE US -->
    <section class="section-bg py-5">
        <div class="container">
            <h2 class="text-center text-primary mb-4">Why Choose Us?</h2>
            <div class="row text-center">
                <div class="col-md-3 mb-3">
                    <i class="fa fa-bolt fa-3x text-primary mb-3"></i>
                    <h5>Real-Time Updates</h5>
                    <p>Stay informed about latest placement activities.</p>
                </div>
                <div class="col-md-3 mb-3">
                    <i class="fa fa-chart-line fa-3x text-primary mb-3"></i>
                    <h5>Smart Analytics</h5>
                    <p>Track your progress with real-time analytics.</p>
                </div>
                <div class="col-md-3 mb-3">
                    <i class="fa fa-users fa-3x text-primary mb-3"></i>
                    <h5>Student–Company Connect</h5>
                    <p>Companies interact directly with TPO.</p>
                </div>
                <div class="col-md-3 mb-3">
                    <i class="fa fa-lock fa-3x text-primary mb-3"></i>
                    <h5>Secure Platform</h5>
                    <p>Your data stays private and protected.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="bg-dark text-white text-center py-3">
        <p class="mb-0">Placement Portal</p>
    </footer>

    <!-- BOOTSTRAP 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

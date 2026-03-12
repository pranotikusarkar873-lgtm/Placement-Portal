<?php
session_start();

// Redirect if already logged in
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: admin_dashboard.php");
    exit();
}

$error = "";

// Login handler
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $admin_user = "tpo";
    $admin_pass = "admin123";

    if ($username === $admin_user && $password === $admin_pass) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_name'] = $admin_user;
        header("Location: admin_dashboard.php");
        exit();
    } else {
        $error = "Invalid username or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Placement Portal</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link href="img/favicon.ico" rel="icon">
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500;600;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <style>
        .admin-login-container {
            max-width: 400px;
            margin: 60px auto;
            padding: 30px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        .admin-login-container h3 {
            text-align: center;
            margin-bottom: 25px;
        }
        .form-group input {
            height: 45px;
        }
        .error-message {
            color: red;
            font-size: 14px;
            text-align: center;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
    <!-- Topbar Start -->
    <div class="container-fluid bg-dark">
        <div class="row py-2 px-lg-5">
            <div class="col-lg-6 text-white text-center text-lg-left mb-2 mb-lg-0">
                <small><i class="fa fa-phone-alt mr-2"></i></small>
                <small class="px-3">|</small>
                <small><i class="fa fa-envelope mr-2"></i></small>
            </div>
               <div class="col-lg-6 text-white text-lg-right">
                <a class="text-white px-2" href="#"><i class="fab fa-facebook-f"></i></a>
                <a class="text-white px-2" href="#"><i class="fab fa-twitter"></i></a>
                <a class="text-white px-2" href="#"><i class="fab fa-linkedin-in"></i></a>
                <a class="text-white px-2" href="#"><i class="fab fa-instagram"></i></a>
                <a class="text-white pl-2" href="#"><i class="fab fa-youtube"></i></a>
            </div>
        </div>
    </div>
    <!-- Topbar End -->

    <!-- Navbar Start -->
    <div class="container-fluid p-0">
        <nav class="navbar navbar-expand-lg bg-white navbar-light py-3 py-lg-0 px-lg-5">
            <a href="index.html" class="navbar-brand ml-lg-3">
                <h3 class="m-0 text-uppercase text-primary"><i class="fa fa-book-reader mr-3"></i>Placement Portal</h3>
            </a>
            <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
             <div class="collapse navbar-collapse justify-content-between px-lg-3" id="navbarCollapse">
                <div class="navbar-nav mx-auto py-0">
                    <a href="index.php" class="nav-item nav-link">Home</a>
                    <a href="about.php" class="nav-item nav-link">About</a>
                    <a href="admin.php" class="nav-item nav-link active">Admin</a>
                    <a href="student_portal.php" class="nav-item nav-link">Student</a>
                    <a href="company.php" class="nav-item nav-link">Companies</a>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Pages</a>
                        <div class="dropdown-menu m-0">
                            <a href="gallery.php" class="dropdown-item">Gallery</a>
                            <a href="department.php" class="dropdown-item">Department</a>
                            <a href="contact.php" class="dropdown-item">Contact Us</a>
                        </div>
                    </div>
                    <a href="feedback.php" class="nav-item nav-link ">Feedback</a>
                </div>
                <div class="dropdown d-none d-lg-block">
    <a 
        class="btn btn-primary py-2 px-4 dropdown-toggle" 
        href="#" 
        role="button" 
        id="loginDropdown" 
        data-bs-toggle="dropdown" 
        aria-expanded="false">
        Login
    </a>

    <ul class="dropdown-menu" aria-labelledby="loginDropdown">
        <li><a class="dropdown-item" href="admin.php">Admin</a></li>
        <li><a class="dropdown-item" href="student_portal.php">Student</a></li>
        <li><a class="dropdown-item" href="company.php">Company</a></li>
    </ul>
</div>
            </div>
        </nav>
    </div>
    <!-- Navbar End -->

    <!-- Admin Login Form Start -->
    <div class="admin-login-container">
        <h3>Admin Login (TPO)</h3>

        <?php if ($error): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" class="form-control" required >
            </div>
            <div class="form-group mt-3">
                <label for="password">Password</label>
                <input type="password" name="password" class="form-control" required >
            </div>
            <button type="submit" class="btn btn-primary w-100 mt-4">Login</button>
        </form>
    </div>
    <!-- Admin Login Form End -->

    <!-- JS Files -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/counterup/counterup.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="js/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

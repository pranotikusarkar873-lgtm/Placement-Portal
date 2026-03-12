<?php
session_start();
$conn = new mysqli("localhost", "root", "", "placement_system");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Handle logout


// Determine which form to show
$show = $_GET['show'] ?? 'login';

// Register
$register_msg = "";
if (isset($_POST['register'])) {
    $name = $_POST['reg_name'] ?? '';
    $email = $_POST['reg_email'] ?? '';
    $password = md5($_POST['reg_password'] ?? '');

    $exists = $conn->query("SELECT * FROM students WHERE email='$email'");
    if ($exists->num_rows > 0) {
        $register_msg = "❌ Email already exists.";
    } else {
        $conn->query("INSERT INTO students (name, email, password) VALUES ('$name', '$email', '$password')");
        $register_msg = "✅ Registered. <a href='?show=login'>Login here</a>";
    }
}

// Login
$login_msg = "";
if (isset($_POST['login'])) {
    $email = $_POST['log_email'] ?? '';
    $password = md5($_POST['log_password'] ?? '');
    $result = $conn->query("SELECT * FROM students WHERE email='$email' AND password='$password'");

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        $_SESSION['student_id'] = $user['id'];
        $_SESSION['student_name'] = $user['name'];
        header("Location: student_dashboard.php");
        exit();
    } else {
        $login_msg = "❌ Invalid credentials.";
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
        .form-container {
            max-width: 500px;
            margin: 30px auto;
            padding: 25px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px #ccc;
        }
        .form-container input {
            margin-bottom: 15px;
        }
        .form-container .msg {
            color: red;
            text-align: center;
        }
        .form-container .success {
            color: green;
            text-align: center;
        }
        .switch-link {
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>

<body>

    <!-- Topbar Start -->
    <div class="container-fluid bg-dark">
        <div class="row py-2 px-lg-5">
            <div class="col-lg-6 text-white text-lg-left mb-2 mb-lg-0">
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
        <nav class="navbar navbar-expand-lg bg-white navbar-light px-lg-5">
            <a href="index.php" class="navbar-brand ml-lg-3">
                <h3 class="text-uppercase text-primary"><i class="fa fa-book-reader mr-3"></i>Placement Portal</h3>
            </a>
            <div class="collapse navbar-collapse justify-content-between px-lg-3">
                <div class="navbar-nav mx-auto py-0">
                    <a href="index.php" class="nav-item nav-link">Home</a>
                    <a href="about.php" class="nav-item nav-link">About</a>
                    <a href="admin.php" class="nav-item nav-link">Admin</a>
                    <a href="student_portal.php" class="nav-item nav-link active">Student</a>
                    <a href="company.php" class="nav-item nav-link">Companies</a>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Pages</a>
                        <div class="dropdown-menu m-0">
                            <a href="gallery.php" class="dropdown-item">Gallery</a>
                            <a href="department.php" class="dropdown-item">Department</a>
                            <a href="contact.php" class="dropdown-item">Contact Us</a>
                        </div>
                    </div>
                    <a href="feedback.php" class="nav-item nav-link">Feedback</a>
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

    <!-- Main Content -->
    <div class="form-container">
        <?php if ($show == 'register'): ?>
            <form method="post">
                <h4 class="text-center">Student Registration</h4>
                <label>Full Name</label>
                <input type="text" name="reg_name" class="form-control"  required>
               <label>Email</label>
                <input type="email" name="reg_email" class="form-control"  required>
               <label>Password</label>
                <input type="password" name="reg_password" class="form-control"  required>
                <button type="submit" name="register" class="btn btn-success btn-block">Register</button>
                <p class="<?= (strpos($register_msg, '✅') !== false) ? 'success' : 'msg' ?>"><?= $register_msg ?></p>
                <div class="switch-link"><a href="?show=login">Already registered? Login here</a></div>
            </form>
        <?php else: ?>

            <form method="post">
                <h4 class="text-center">Student Login</h4>
                <label>Email</label>
                <input type="email" name="log_email" class="form-control" required>
               <label>Password</label>
                <input type="password" name="log_password" class="form-control"  required>
                <button type="submit" name="login" class="btn btn-primary btn-block">Login</button>
                <p class="msg"><?= $login_msg ?></p>
                <div class="switch-link"><a href="?show=register">Not registered? Register</a></div>
            </form>
        <?php endif; ?>
    </div>

    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="fa fa-angle-double-up"></i></a>

    <!-- JS Scripts -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="js/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

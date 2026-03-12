<?php
session_start();
$conn = new mysqli("localhost", "root", "", "placement_system");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$reg_success = $reg_error = $login_error = "";

// Handle Registration
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['register'])) {
    $name = trim($_POST['reg_name']);
    $email = trim($_POST['reg_email']);
    $password = password_hash($_POST['reg_password'], PASSWORD_BCRYPT);

    $stmt = $conn->prepare("INSERT INTO companies (name, email, password, status) VALUES (?, ?, ?, 'pending')");
    $stmt->bind_param("sss", $name, $email, $password);
    if ($stmt->execute()) {
        $reg_success = "Registration successful! Please wait for admin approval. <a href='#' onclick='toggleForms()'>Click here to login</a>";
    } else {
        $reg_error = "Registration failed. Email may already exist.";
    }
    $stmt->close();
}

// Handle Login
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['login'])) {
    $email = trim($_POST['login_email']);
    $password = trim($_POST['login_password']);

    $stmt = $conn->prepare("SELECT id, name, password, status FROM companies WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id, $name, $hashed_password, $status);
        $stmt->fetch();

       if (strtolower($status) === 'approved' && password_verify($password, $hashed_password)) {
 
            $_SESSION['company_id'] = $id;
            $_SESSION['company_name'] = $name;

            // Fetch approval message
            $msg_stmt = $conn->prepare("SELECT approval_message FROM companies WHERE id = ?");
            $msg_stmt->bind_param("i", $id);
            $msg_stmt->execute();
            $msg_stmt->bind_result($approval_msg);
            $msg_stmt->fetch();
            $_SESSION['approval_message'] = $approval_msg;
            $msg_stmt->close();

            header("Location: company_dashboard.php");
            exit();
        } else {
            $login_error = "Invalid credentials or not approved yet.";
        }
    } else {
        $login_error = "No account found with that email.";
    }

    $stmt->close();
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .toggle-link { color: #007bff; cursor: pointer; text-decoration: underline; }
        .auth-form { max-width: 500px; margin: auto; background: #fff; padding: 30px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
    </style>
</head>
<!-- Bootstrap 5 JS (Includes Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<body class="bg-light">
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
                    <a href="admin.php" class="nav-item nav-link ">Admin</a>
                    <a href="student_portal.php" class="nav-item nav-link">Student</a>
                    <a href="company.php" class="nav-item nav-link active">Companies</a>
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

    <!-- Login/Register Form -->
    <div class="container mt-5 mb-5">
        <div class="auth-form">
            <!-- Login Form -->
            <form method="POST" id="loginForm">
                <h4 class="text-center mb-4">Company Login</h4>
                <?php if ($login_error): ?><div class="alert alert-danger"><?= $login_error ?></div><?php endif; ?>
                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="login_email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Password</label>
                    <input type="password" name="login_password" class="form-control" required>
                </div>
                <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
                <p class="mt-3 text-center">Not registered? <span class="toggle-link" onclick="toggleForms()">Register here</span></p>
            </form>

            <!-- Register Form -->
            <form method="POST" id="registerForm" style="display:none;">
                <h4 class="text-center mb-4">Company Registration</h4>
                <?php if ($reg_success): ?><div class="alert alert-success"><?= $reg_success ?></div><?php endif; ?>
                <?php if ($reg_error): ?><div class="alert alert-danger"><?= $reg_error ?></div><?php endif; ?>
                <div class="mb-3">
                    <label>Company Name</label>
                    <input type="text" name="reg_name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="reg_email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Password</label>
                    <input type="password" name="reg_password" class="form-control" required>
                </div>
                <button type="submit" name="register" class="btn btn-success w-100">Register</button>
                <p class="mt-3 text-center">Already registered? <span class="toggle-link" onclick="toggleForms()">Login here</span></p>
            </form>
        </div>
    </div>

    <!-- JS -->
    <script>
        function toggleForms() {
            const loginForm = document.getElementById('loginForm');
            const registerForm = document.getElementById('registerForm');
            if (loginForm.style.display === 'none') {
                loginForm.style.display = 'block';
                registerForm.style.display = 'none';
            } else {
                loginForm.style.display = 'none';
                registerForm.style.display = 'block';
            }
        }
    </script>
</body>
</html>

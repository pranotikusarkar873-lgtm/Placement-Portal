<?php
session_start();

// Auto-redirect to dashboard if already logged in
if (isset($_SESSION['teacher_id']) && basename($_SERVER['PHP_SELF']) === 'department.php') {
    header("Location: department_dashboard.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "placement_system");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$reg_msg = $login_msg = "";

// Handle registration/login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['register'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $stmt = $conn->prepare("SELECT * FROM teacher WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $check = $stmt->get_result();

        if ($check->num_rows > 0) {
            $reg_msg = "Email already registered.";
        } else {
            $stmt = $conn->prepare("INSERT INTO teacher (name, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $name, $email, $password);
            $stmt->execute();
            $reg_msg = "Registration successful! Please log in.";
        }
    }

    if (isset($_POST['login'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $stmt = $conn->prepare("SELECT * FROM teacher WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                $_SESSION['teacher_name'] = $row['name'];
                $_SESSION['teacher_id'] = $row['id'];
                header("Location: department_dashboard.php");
                exit();
            } else {
                $login_msg = "Invalid credentials.";
            }
        } else {
            $login_msg = "No account found.";
        }
    }
}

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: department.php");
    exit();
}

// Determine current page for navbar
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Placement Portal</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Google Fonts & Font Awesome -->
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500;600;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">

    <!-- Bootstrap 4 -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>

    <!-- Topbar -->
    <div class="container-fluid bg-dark">
        <div class="row py-2 px-lg-5">
            <div class="col-lg-6 text-center text-lg-left text-white mb-2 mb-lg-0">
                <small><i class="fa fa-phone-alt mr-2"></i></small>
                <small class="px-3">|</small>
                <small><i class="fa fa-envelope mr-2"></i></small>
            </div>
            <div class="col-lg-6 text-center text-lg-right">
                <div class="d-inline-flex align-items-center">
                    <a class="text-white px-2" href="#"><i class="fab fa-facebook-f"></i></a>
                    <a class="text-white px-2" href="#"><i class="fab fa-twitter"></i></a>
                    <a class="text-white px-2" href="#"><i class="fab fa-linkedin-in"></i></a>
                    <a class="text-white px-2" href="#"><i class="fab fa-instagram"></i></a>
                    <a class="text-white pl-2" href="#"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
        </div>
    </div>

    <!-- Navbar -->
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
                    <a href="index.php" class="nav-item nav-link <?= ($current_page == 'index.php') ? 'active' : '' ?>">Home</a>
                    <a href="about.php" class="nav-item nav-link <?= ($current_page == 'about.php') ? 'active' : '' ?>">About</a>
                    <a href="admin.php" class="nav-item nav-link <?= ($current_page == 'admin.php') ? 'active' : '' ?>">Admin</a>
                    <a href="student_portal.php" class="nav-item nav-link <?= ($current_page == 'student_portal.php') ? 'active' : '' ?>">Student</a>
                    <a href="company.php" class="nav-item nav-link <?= ($current_page == 'company.php') ? 'active' : '' ?>">Companies</a>

                    <!-- ✅ Fixed Dropdown -->
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle <?= in_array($current_page, ['gallery.php', 'department.php', 'contact.php']) ? 'active' : '' ?>" data-toggle="dropdown">Pages</a>
                        <div class="dropdown-menu m-0">
                            <a href="gallery.php" class="dropdown-item <?= ($current_page == 'gallery.php') ? 'active' : '' ?>">Gallery</a>
                            <a href="department.php" class="dropdown-item <?= ($current_page == 'department.php') ? 'active' : '' ?>">Department</a>
                            <a href="contact.php" class="dropdown-item <?= ($current_page == 'contact.php') ? 'active' : '' ?>">Contact Us</a>
                        </div>
                    </div>

                    <a href="feedback.php" class="nav-item nav-link <?= ($current_page == 'feedback.php') ? 'active' : '' ?>">Feedback</a>
                </div>

                <?php if (isset($_SESSION['teacher_id'])): ?>
                    <a href="department_dashboard.php" class="btn btn-success py-2 px-4 mr-2 d-none d-lg-block">Dashboard</a>
                    <a href="?logout=true" class="btn btn-danger py-2 px-4 d-none d-lg-block">Logout</a>
                <?php else: ?>
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
                <?php endif; ?>
            </div>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="container mt-5">
        <?php if (isset($_SESSION['teacher_id'])): ?>
            <div class="card p-4">
                <h4>Welcome, <?php echo $_SESSION['teacher_name']; ?> 👋</h4>
                <p>This is your dashboard. You can view student data, job listings, and reports here.</p>
                <a href="department_dashboard.php" class="btn btn-primary mt-3">Go to Department Dashboard</a>
            </div>
        <?php else: ?>
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <!-- Login Form -->
                    <div class="card p-4" id="login-form">
                        <h4 class="mb-3">Teacher Login</h4>
                        <?php if ($login_msg) echo "<div class='alert alert-danger'>$login_msg</div>"; ?>
                        <form method="POST">
                            <div class="form-group">
                                <label>Email:</label>
                                <input type="email" name="email" required class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Password:</label>
                                <input type="password" name="password" required class="form-control">
                            </div>
                            <button type="submit" name="login" class="btn btn-primary btn-block">Login</button>
                        </form>
                        <p class="mt-3 text-center">Not registered? <a href="#" onclick="toggleForms()">Click here to register</a></p>
                    </div>

                    <!-- Registration Form -->
                    <div class="card p-4 d-none" id="register-form">
                        <h4 class="mb-3">Teacher Registration</h4>
                        <?php if ($reg_msg) echo "<div class='alert alert-info'>$reg_msg</div>"; ?>
                        <form method="POST">
                            <div class="form-group">
                                <label>Name:</label>
                                <input type="text" name="name" required class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Email:</label>
                                <input type="email" name="email" required class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Password:</label>
                                <input type="password" name="password" required class="form-control">
                            </div>
                            <button type="submit" name="register" class="btn btn-success btn-block">Register</button>
                        </form>
                        <p class="mt-3 text-center">Already registered? <a href="#" onclick="toggleForms()">Click here to login</a></p>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script>
        function toggleForms() {
            document.getElementById('login-form').classList.toggle('d-none');
            document.getElementById('register-form').classList.toggle('d-none');
        }
    </script>

    <!-- Bootstrap JS (for dropdowns) -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
</body>
</html>

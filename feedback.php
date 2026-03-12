<?php
$conn = new mysqli("localhost", "root", "", "placement_system");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$msg = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $conn->real_escape_string($_POST['name']);
    $comment = $conn->real_escape_string($_POST['comment']);
    $user_type = $conn->real_escape_string($_POST['user_type']);

    $sql = "INSERT INTO feedbacks (name, comment, user_type) VALUES ('$name', '$comment', '$user_type')";
    if ($conn->query($sql) === TRUE) {
        $msg = "Thank you for your feedback!";
    } else {
        $msg = "Error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Placement Portal</title>

    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500;600;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet"> 

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">

    <!-- Libraries -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Bootstrap + Custom CSS -->
    <link href="css/style.css" rel="stylesheet">

    <!-- Custom inline styles -->
    <style>
        .form-container {
            background-color: #fff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
            margin-bottom: 60px;
        }

        .form-container textarea {
            resize: vertical;
            min-height: 120px;
        }

        .feedback-entry {
            background-color: #f8f9fa;
            padding: 15px;
            margin-bottom: 15px;
            border-left: 4px solid #007bff;
            border-radius: 6px;
        }
    </style>
</head>

<body>
    <!-- Topbar Start -->
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
                    <a href="admin.php" class="nav-item nav-link">Admin</a>
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
                    <a href="feedback.php" class="nav-item nav-link active">Feedback</a>
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

    <!-- Header Start -->
    <div class="jumbotron jumbotron-fluid page-header position-relative overlay-bottom mb-5">
        <div class="container text-center py-5">
            <h1 class="display-3 text-white">Feedback</h1>
            <div class="d-inline-flex text-white">
                <p class="m-0 text-uppercase"><a class="text-white" href="index.php">Home</a></p>
                <i class="fa fa-angle-double-right pt-1 px-3"></i>
                <p class="m-0 text-uppercase">Feedback</p>
            </div>
        </div>
    </div>
    <!-- Header End -->

    <!-- Feedback Form Section Start -->
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="form-container">
                    <h2 class="text-center mb-4">Submit Feedback</h2>
                    <form method="POST">
                        <div class="form-group">
                            <label for="user_type">You are:</label>
                            <select class="form-control" name="user_type" id="user_type" required>
                                <option value="">Select</option>
                                <option value="Student">Student</option>
                                <option value="Admin">Admin</option>
                                <option value="Company">Company</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="name">Your Name:</label>
                            <input type="text" class="form-control" name="name" id="name" required>
                        </div>
                        <div class="form-group">
                            <label for="comment">Your Comment:</label>
                            <textarea class="form-control" name="comment" id="comment" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Submit Feedback</button>
                    </form>

                    <?php if (!empty($msg)) echo "<div class='alert alert-success mt-3 text-center'>$msg</div>"; ?>

                    <div class="mt-4">
                        <h4 class="text-center">Recent Feedback</h4>
                        <?php
                        $result = $conn->query("SELECT * FROM feedbacks ORDER BY date DESC LIMIT 5");
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<div class='feedback-entry'>
                                        <strong>" . htmlspecialchars($row['name']) . "</strong>
                                        <small class='d-block text-muted'>(" . htmlspecialchars($row['user_type']) . ")</small>
                                        <small class='d-block text-muted'>" . $row['date'] . "</small>
                                        <p class='mb-0'>" . nl2br(htmlspecialchars($row['comment'])) . "</p>
                                      </div>";
                            }
                        } else {
                            echo "<p class='text-center text-muted'>No feedback yet.</p>";
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Feedback Form Section End -->

    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary rounded-0 btn-lg-square back-to-top">
        <i class="fa fa-angle-double-up"></i>
    </a>

    <!-- JavaScript -->
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

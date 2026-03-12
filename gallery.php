<?php
session_start();

// Database connection
$conn = new mysqli("localhost", "root", "", "placement_system");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Placement Portal</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500;600;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

    <style>
        .gallery {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            margin: 30px 0;
        }
        .gallery-item {
            width: 200px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            padding: 10px;
            text-align: center;
        }
        .gallery-item img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 8px;
        }
        .gallery-item h3 { font-size: 16px; margin-top: 10px; }
        .gallery-item p { font-size: 14px; margin: 2px 0; }
        .back-arrow i { margin-right: 6px; }
        .back-arrow:hover { color: #0056b3; }
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
            <a href="index.php" class="navbar-brand ml-lg-3">
                <h3 class="m-0 text-uppercase text-primary"><i class="fa fa-book-reader mr-3"></i>Placement Portal</h3>
            </a>
            <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
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
                        <a href="#" class="nav-link dropdown-toggle active" data-bs-toggle="dropdown">Pages</a>
                        <div class="dropdown-menu m-0">
                            <a href="gallery.php" class="dropdown-item active">Gallery</a>
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
              
            </div>
        </nav>
    </div>
    <!-- Navbar End -->

    <!-- Back Arrow -->
    <div class="container mt-3">
        <a href="index.php" class="back-arrow" style="text-decoration: none; color: #007bff; font-size: 18px;">
            <i class="fas fa-arrow-left"></i> Back to Home
        </a>
    </div>

    <div class="container">
        <h2 class="text-center mt-4">Placed Students Gallery</h2>

        <!-- Display gallery -->
        <div class="gallery">
            <?php
            $result = $conn->query("SELECT * FROM placed_students ORDER BY id DESC");
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    // Check if photo exists
                    $photoPath = "uploads/" . $row['photo'];
                    if (!file_exists($photoPath) || empty($row['photo'])) {
                        $photoPath = "uploads/default.png"; // fallback image
                    }

                    echo '<div class="gallery-item">';
                    echo '<img src="' . htmlspecialchars($photoPath) . '" alt="' . htmlspecialchars($row['student_name']) . '">';
                    echo '<h3>' . htmlspecialchars($row['student_name']) . '</h3>';
                    echo '<p><b>Company:</b> ' . htmlspecialchars($row['company_name']) . '</p>';
                    echo '<p><b>Position:</b> ' . htmlspecialchars($row['job_role']) . '</p>';
                   // Extract only the year
$year = date("Y", strtotime($row['placement_date']));
echo '<p><b>Year:</b> ' . htmlspecialchars($year) . '</p>';

                    echo '<p><b>Package:</b> ' . htmlspecialchars($row['package']) . '</p>';
                    echo '</div>';
                }
            } else {
                echo "<p style='text-align:center;'>No placed students found yet.</p>";
            }
            $conn->close();
            ?>
        </div>
    </div>
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

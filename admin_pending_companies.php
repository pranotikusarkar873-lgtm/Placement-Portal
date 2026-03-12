<?php
$conn = new mysqli("localhost", "root", "", "placement_system");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['approve_id'])) {
    $id = $_POST['approve_id'];
    $message = $_POST['approval_message'];
    $stmt = $conn->prepare("UPDATE companies SET status='approved', approval_message=? WHERE id=?");
    $stmt->bind_param("si", $message, $id);
    $stmt->execute();
    $stmt->close();
}

$result = $conn->query("SELECT * FROM companies WHERE status='pending'");
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
                            <a href="gallery.html" class="dropdown-item">Gallery</a>
                            <a href="department.html" class="dropdown-item">Department</a>
                            <a href="contact.html" class="dropdown-item">Contact Us</a>
                        </div>
                    </div>
                    <a href="feedback.php" class="nav-item nav-link active">Feedback</a>
                </div>
                <a href="#" class="btn btn-primary py-2 px-4 d-none d-lg-block">Login</a>
            </div>
        </nav>
    </div>
    <!-- Navbar End -->
     <center>
    <h2 style="color: blue;">Pending Companies</h2>


<table border="1" cellpadding="10" >
    <tr><th>ID</th><th>Name</th><th>Email</th><th>Action</th></tr>
    <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id']; ?></td>
            <td><?= $row['name']; ?></td>
            <td><?= $row['email']; ?></td>
            <td>
                <form method="POST">
                    <input type="hidden" name="approve_id" value="<?= $row['id']; ?>">
                    <input type="text" name="approval_message" placeholder="Approval message" required>
                    <button type="submit">Approve</button>
                </form>
            </td>
        </tr>
    <?php endwhile; ?>
</table><br><br>
<div class="text-center">
        <a href="admin_dashboard.php" class="btn btn-primary back-btn"></i> Back to Dashboard</a>
    </div>
    </center>
    </body>
    </html>
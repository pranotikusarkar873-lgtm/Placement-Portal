<?php
session_start();

if (!isset($_SESSION['company_id'])) {
    header("Location: company_portal.php");
    exit();
}

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: company.php");
    exit();
}

$company_id = $_SESSION['company_id'];
$company_name = $_SESSION['company_name'];
$approval_message = $_SESSION['approval_message'] ?? "";

$conn = new mysqli("localhost", "root", "", "placement_system");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle new job posting
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['post_job'])) {
    $job_title = $conn->real_escape_string($_POST['job_title']);
    $job_description = $conn->real_escape_string($_POST['job_description']);
    $location = $conn->real_escape_string($_POST['location']);
    $posted_date = date("Y-m-d");

    $insert = "INSERT INTO jobs (job_title, job_description, company_name, location, posted_date)
               VALUES ('$job_title', '$job_description', '$company_name', '$location', '$posted_date')";
    $conn->query($insert);
}

// Get all jobs
$sql = "SELECT job_title, job_description, company_name, location, posted_date FROM jobs ORDER BY posted_date DESC";
$result = $conn->query($sql);

// Get applications for this company
$application_sql = "
SELECT 
    ja.applied_date,
    st.name AS student_name,
    st.email AS student_email,
    sp.phone AS student_phone,
    sp.resume_path AS student_resume,
    j.job_title
FROM job_applications ja
JOIN jobs j ON ja.job_id = j.id
JOIN students st ON ja.student_id = st.id
LEFT JOIN student_profiles sp ON sp.student_id = st.id
WHERE j.company_name = '$company_name'
ORDER BY ja.applied_date DESC";

$applications = $conn->query($application_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Placement Portal</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <link href="img/favicon.ico" rel="icon">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500;600;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet"> 
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
            <h3 class="m-0 text-uppercase text-primary">
                <i class="fa fa-book-reader mr-3"></i>Placement Portal
            </h3>
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
                <a href="company.php" class="nav-item nav-link active">Companies</a>
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
            <a href="?logout=true" class="btn btn-danger py-2 px-4">Logout</a>
        </div>
    </nav>
</div>
<!-- Navbar End -->

<div class="container mt-5">
    <h2 class="text-primary">Welcome, <?= htmlspecialchars($company_name); ?>!</h2>

    <?php if (!empty($approval_message)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($approval_message); ?></div>
    <?php endif; ?>

    <!-- Job Posting Form -->
    <div class="card mt-4 mb-5">
        <div class="card-header bg-info text-white">Post a New Job</div>
        <div class="card-body">
            <form method="POST">
                <div class="form-group">
                    <label>Job Title</label>
                    <input type="text" class="form-control" name="job_title" required>
                </div>
                <div class="form-group">
                    <label>Job Description</label>
                    <textarea class="form-control" name="job_description" rows="4" required></textarea>
                </div>
                <div class="form-group">
                    <label>Job Location</label>
                    <input type="text" class="form-control" name="location" required>
                </div>
                <button type="submit" name="post_job" class="btn btn-success">Post Job</button>
            </form>
        </div>
    </div>

    <!-- Posted Jobs -->
    <h4 class="text-secondary mb-3">All Jobs Posted by Companies</h4>
    <?php if ($result && $result->num_rows > 0): ?>
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>Job Title</th>
                    <th>Description</th>
                    <th>Company</th>
                    <th>Location</th>
                    <th>Posted Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['job_title']); ?></td>
                        <td><?= htmlspecialchars($row['job_description']); ?></td>
                        <td><?= htmlspecialchars($row['company_name']); ?></td>
                        <td><?= htmlspecialchars($row['location']); ?></td>
                        <td><?= htmlspecialchars($row['posted_date']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-info text-center">No jobs found.</div>
    <?php endif; ?>

    <!-- Applications -->
    <h4 class="text-secondary mt-5 mb-3">Students Who Applied to Your Jobs</h4>
    <?php if ($applications && $applications->num_rows > 0): ?>
        <table class="table table-bordered table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>Job Title</th>
                    <th>Student Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Resume</th>
                    <th>Applied Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $applications->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['job_title']); ?></td>
                        <td><?= htmlspecialchars($row['student_name']); ?></td>
                        <td><?= htmlspecialchars($row['student_email']); ?></td>
                        <td><?= htmlspecialchars($row['student_phone'] ?? 'Not Provided'); ?></td>
                        <td>
                            <?php
                            if (!empty($row['student_resume']) && file_exists($row['student_resume'])) {
                                echo '<a href="' . $row['student_resume'] . '" target="_blank">View Resume</a>';
                            } else {
                                echo '<span class="text-muted">N/A</span>';
                            }
                            ?>
                        </td>
                        <td><?= htmlspecialchars($row['applied_date']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-info text-center">No student applications found.</div>
    <?php endif; ?>
</div>

<!-- jQuery and Bootstrap JS for dropdown -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php $conn->close(); ?>

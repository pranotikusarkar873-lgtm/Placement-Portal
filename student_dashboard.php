<?php
session_start();

if (!isset($_SESSION['student_id'])) {
    header("Location: student_portal.php");
    exit();
}
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: student_portal.php");
    exit();
}
$student_id = $_SESSION['student_id'];
$student_name = $_SESSION['student_name'];

$conn = new mysqli("localhost", "root", "", "placement_system");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$update_msg = "";

// Handle profile update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_profile'])) {
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $skills = $_POST['skills'];
    $job_role = $_POST['job_role'];

    $resume_path = "";
    if (isset($_FILES['resume']) && $_FILES['resume']['error'] === UPLOAD_ERR_OK) {
        $resume_name = basename($_FILES['resume']['name']);
        $target_dir = __DIR__ . "/uploads/resumes/";
        $web_path_prefix = "uploads/resumes/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $new_filename = time() . "_" . $resume_name;
        $full_resume_path = $target_dir . $new_filename;
        $resume_path = $web_path_prefix . $new_filename;
        move_uploaded_file($_FILES['resume']['tmp_name'], $full_resume_path);
    }

    // Check if profile exists
    $check_sql = "SELECT * FROM student_profiles WHERE student_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("i", $student_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        // Update existing profile
        if (!empty($resume_path)) {
            $update_sql = "UPDATE student_profiles SET phone=?, address=?, skills=?, job_role=?, resume_path=? WHERE student_id=?";
            $stmt = $conn->prepare($update_sql);
            $stmt->bind_param("sssssi", $phone, $address, $skills, $job_role, $resume_path, $student_id);
        } else {
            $update_sql = "UPDATE student_profiles SET phone=?, address=?, skills=?, job_role=? WHERE student_id=?";
            $stmt = $conn->prepare($update_sql);
            $stmt->bind_param("ssssi", $phone, $address, $skills, $job_role, $student_id);
        }
    } else {
        // Insert new profile
        $insert_sql = "INSERT INTO student_profiles (student_id, phone, address, skills, job_role, resume_path) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_sql);
        $stmt->bind_param("isssss", $student_id, $phone, $address, $skills, $job_role, $resume_path);
    }

    if ($stmt->execute()) {
        $update_msg = "Profile saved successfully!";
    } else {
        $update_msg = "Error saving profile: " . $stmt->error;
    }
}

// Handle job application form
// Handle job application
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['apply_job_id'])) {

    $job_id = intval($_POST['apply_job_id']);

    // Prevent duplicate application
    $check = $conn->prepare("SELECT id FROM job_applications WHERE student_id=? AND job_id=?");
    $check->bind_param("ii", $student_id, $job_id);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        echo "<script>alert('You have already applied for this job');</script>";
    } else {

        $stmt = $conn->prepare(
            "INSERT INTO job_applications (student_id, job_id, status) VALUES (?, ?, 'Applied')"
        );
        $stmt->bind_param("ii", $student_id, $job_id);

        if ($stmt->execute()) {
            echo "<script>alert('Application submitted successfully!');</script>";
        } else {
            echo "<script>alert('Error applying for job');</script>";
        }
    }
}


// Fetch email from registration table
$email_result = $conn->query("SELECT email FROM students WHERE id = $student_id");
$email_row = $email_result->fetch_assoc();
$student_email = $email_row['email'] ?? '';

// Fetch profile if exists
$profile_sql = "SELECT * FROM student_profiles WHERE student_id = $student_id";
$profile_result = $conn->query($profile_sql);
$profile = $profile_result->fetch_assoc();

// Fetch posted jobs
$jobs = $conn->query("SELECT * FROM jobs ORDER BY posted_date DESC");

// Fetch applied jobs
$applied_jobs = $conn->query("SELECT job_id FROM job_applications WHERE student_id = $student_id");
$applied_job_ids = [];
while ($row = $applied_jobs->fetch_assoc()) {
    $applied_job_ids[] = $row['job_id'];
}

// Fetch notifications
$notifications_result = $conn->query("
    SELECT * FROM notifications 
    WHERE student_id = $student_id 
    ORDER BY created_at DESC
");
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
</head>
<body>

<!-- Navbar -->
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
                <a href="admin.php" class="nav-item nav-link ">Admin</a>
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
                <a href="feedback.php" class="nav-item nav-link ">Feedback</a>
            </div>
            <a href="?logout=1" class="btn btn-danger py-2 px-4 d-none d-lg-block">Logout</a>
        </div>
    </nav>
</div>

<div class="container mt-4">
<h2 class="text-primary">Welcome, <?= htmlspecialchars($student_name); ?>!</h2>

<!-- Notifications Section -->
<?php if($notifications_result->num_rows > 0): ?>
    <div class="alert alert-info mt-3">
        <h5><i class="fas fa-bell"></i> Notifications</h5>
        <ul class="mb-0">
            <?php while($row = $notifications_result->fetch_assoc()): ?>
                <li>
                    <?= htmlspecialchars($row['message']); ?> 
                    <small class="text-muted">(<?= $row['created_at']; ?>)</small>
                </li>
            <?php endwhile; ?>
        </ul>
    </div>

    <?php 
    // Mark notifications as read
    $conn->query("UPDATE notifications SET is_read=1 WHERE student_id=$student_id");
    ?>
<?php endif; ?>

<!-- Profile Section -->
<div class="card mt-4">
    <div class="card-header bg-info text-white">Your Profile</div>
    <div class="card-body">
        <?php if (!empty($update_msg)): ?>
            <div class="alert alert-success"><?= $update_msg; ?></div>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" value="<?= htmlspecialchars($student_email); ?>" class="form-control" readonly>
            </div>

            <div class="form-group">
                <label>Phone:</label>
                <input type="text" name="phone" value="<?= htmlspecialchars($profile['phone'] ?? ''); ?>" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Address:</label>
                <textarea name="address" class="form-control" required><?= htmlspecialchars($profile['address'] ?? ''); ?></textarea>
            </div>

            <div class="form-group">
                <label>Skills:</label>
                <input type="text" name="skills" value="<?= htmlspecialchars($profile['skills'] ?? ''); ?>" 
                    placeholder="e.g., HTML, CSS, JavaScript, PHP" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Preferred Job Role:</label>
                <select name="job_role" class="form-control" required>
                    <option value="">--Select Job Role--</option>
                    <?php
                        $roles = ["Web Developer","Full Stack Developer","Software Tester","Data Analyst","AI/ML Engineer","Mobile App Developer","Other"];
                        foreach($roles as $role){
                            $selected = (isset($profile['job_role']) && $profile['job_role']==$role) ? "selected" : "";
                            echo "<option value='$role' $selected>$role</option>";
                        }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label>Upload Resume (PDF only):</label>
                <input type="file" name="resume" accept=".pdf" class="form-control">
                <?php if (!empty($profile['resume_path'])): ?>
                    <small class="form-text text-muted">
                        Current Resume: 
                        <a href="<?= htmlspecialchars($profile['resume_path']); ?>" target="_blank">
                            <?= htmlspecialchars(basename($profile['resume_path'])); ?>
                        </a>
                    </small>
                <?php endif; ?>
            </div>

            <button type="submit" name="update_profile" class="btn btn-primary btn-sm">Save Profile</button>
        </form>
    </div>
</div>

<!-- Jobs Section -->
<div class="card mt-5">
    <div class="card-header bg-success text-white">Available Job Opportunities</div>
    <div class="card-body">
        <?php if ($jobs && $jobs->num_rows > 0): ?>
            <table class="table table-bordered table-striped mt-3">
                <thead class="thead-dark">
                    <tr>
                        <th>Job Title</th>
                        <th>Description</th>
                        <th>Company</th>
                        <th>Location</th>
                        <th>Posted Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($job = $jobs->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($job['job_title']); ?></td>
                            <td><?= htmlspecialchars($job['job_description']); ?></td>
                            <td><?= htmlspecialchars($job['company_name']); ?></td>
                            <td><?= htmlspecialchars($job['location']); ?></td>
                            <td><?= htmlspecialchars($job['posted_date']); ?></td>
                            <td>
                                <?php if (in_array($job['id'], $applied_job_ids)): ?>
                                    <button class="btn btn-secondary btn-sm" disabled>Already Applied</button>
                                <?php else: ?>
                                    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#applyModal" data-jobid="<?= $job['id']; ?>">Apply</button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-center text-muted">No jobs posted yet.</p>
        <?php endif; ?>
    </div>
</div>

</div>

<!-- Application Modal -->
<div class="modal fade" id="applyModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form method="POST">
        <div class="modal-header">
          <h5 class="modal-title">Job Application</h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="apply_job_id" id="apply_job_id">
        
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Apply</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
$('#applyModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget)
    var jobid = button.data('jobid')
    $('#apply_job_id').val(jobid)
})
</script>
</body>
</html>
<?php $conn->close(); ?>

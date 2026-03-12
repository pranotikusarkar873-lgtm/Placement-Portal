<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin.php");
    exit();
}

// Database connection
$conn = new mysqli("localhost", "root", "", "placement_system");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$success_msg = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_name = $_POST['student_name'];
    $company_name = $_POST['company_name'];
    $position = $_POST['position'];
    $year = $_POST['year'];
    $package = $_POST['package']; // new field

    $target_dir = "uploads/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $photo_name = basename($_FILES["photo"]["name"]);
    $target_file = $target_dir . $photo_name;

    if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
        // Insert student including photo filename and package
        $stmt = $conn->prepare("INSERT INTO placed_students (student_name, company_name, job_role, placement_date, package, photo) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $student_name, $company_name, $position, $year, $package, $photo_name);

        if ($stmt->execute()) {
            $success_msg = "Placed student added successfully!";
        } else {
            $success_msg = "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $success_msg = "Error uploading photo!";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Placement Portal</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow p-4">
        <h3 class="mb-3"><i class="fas fa-user-check text-success"></i> Add Placed Student</h3>
       

        <?php if ($success_msg): ?>
            <div class="alert alert-success"><?php echo $success_msg; ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Student Name</label>
                    <input type="text" name="student_name" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Company Name</label>
                    <input type="text" name="company_name" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Position</label>
                    <input type="text" name="position" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Year</label>
                    <input type="text" name="year" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Package</label>
                    <input type="text" name="package" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Upload Photo</label>
                    <input type="file" name="photo" class="form-control" accept="image/*" required>
                </div>
            </div>
            <button type="submit" class="btn btn-success">Add Student</button>
            
    <div class="text-center">
        <a href="admin_dashboard.php" class="btn btn-primary back-btn"></i> Back to Dashboard</a>
    </div>
        </form>
    </div>
</div>

</body>
</html>

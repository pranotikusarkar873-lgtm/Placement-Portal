<?php
session_start();

// ----------------------------
// 1️⃣ SESSION CHECK
// ----------------------------
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin.php");
    exit();
}

// ----------------------------
// 2️⃣ DATABASE CONNECTION
// ----------------------------
$conn = new mysqli("localhost", "root", "", "placement_system");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ----------------------------
// 3️⃣ ENSURE notifications TABLE EXISTS
// ----------------------------
$conn->query("
CREATE TABLE IF NOT EXISTS notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_read BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (student_id) REFERENCES student_profiles(student_id) ON DELETE CASCADE
) ENGINE=InnoDB;
");

// ----------------------------
// 4️⃣ NOTIFICATION HANDLER
// ----------------------------
$notify_msg = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_notification'])) {
    $selected_students = $_POST['students'] ?? [];
    $company_name = $_POST['company_name'] ?? '';
    $job_role = $_POST['job_role_notify'] ?? '';

    if (!empty($selected_students)) {
        foreach ($selected_students as $student_id) {
            $stmt = $conn->prepare("
                SELECT sp.*, s.name AS full_name
                FROM student_profiles sp
                JOIN students s ON sp.student_id = s.id
                WHERE sp.student_id = ?
            ");
            $stmt->bind_param("i", $student_id);
            $stmt->execute();
            $student = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            if ($student) {
                $student_name = $student['full_name'];
                $student_email = $student['email'] ?? '';

                $subject = "Job Opportunity Notification";
                $message = "Hello " . $student_name . ",\n\n" 
                         . $company_name . " is looking for " . $job_role . ".\n"
                         . "Please check the placement portal for details.\n\nRegards,\nAdmin";
                $headers = "From: admin@example.com";

                if (!empty($student_email)) {
                    @mail($student_email, $subject, $message, $headers);
                }

                $stmt2 = $conn->prepare("INSERT INTO notifications (student_id, message) VALUES (?, ?)");
                $stmt2->bind_param("is", $student_id, $message);
                $stmt2->execute();
                $stmt2->close();
            }
        }
        $notify_msg = "Notifications sent to selected students!";
    } else {
        $notify_msg = "No students selected!";
    }
}

// ----------------------------
// 5️⃣ JOB ROLE FILTER
// ----------------------------
$filter_job_role = $_GET['job_role'] ?? '';
$roles = ["Web Developer","Full Stack Developer","Software Tester","Data Analyst","AI/ML Engineer","Mobile App Developer","Other"];

// Fetch students with skills
if (!empty($filter_job_role)) {
    $stmt = $conn->prepare("
        SELECT sp.student_id, sp.email, sp.phone, sp.address, sp.skills, sp.job_role, sp.resume_path,
               s.name AS full_name
        FROM student_profiles sp
        JOIN students s ON sp.student_id = s.id
        WHERE sp.job_role LIKE ?
        ORDER BY sp.student_id DESC
    ");
    $like_role = "%".$filter_job_role."%";
    $stmt->bind_param("s", $like_role);
} else {
    $stmt = $conn->prepare("
        SELECT sp.student_id, sp.email, sp.phone, sp.address, sp.skills, sp.job_role, sp.resume_path,
               s.name AS full_name
        FROM student_profiles sp
        JOIN students s ON sp.student_id = s.id
        ORDER BY sp.student_id DESC
    ");
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Placement Portal</title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">
<style>
table { width:100%; border-collapse: collapse; margin-top:20px;}
table, th, td { border:1px solid #ccc;}
th, td { padding:10px; text-align:center;}
.alert { background:#d4edda; color:#155724; padding:10px; border-radius:5px; margin-bottom:10px;}
.btn { padding:10px 15px; cursor:pointer; border:none; border-radius:5px;}
.btn-success { background:#28a745; color:#fff;}
</style>
</head>
<body>
<div class="container mt-4">
    <h2>Student Profiles & Notification</h2>

    <?php if(!empty($notify_msg)): ?>
        <div class="alert"><?= htmlspecialchars($notify_msg) ?></div>
    <?php endif; ?>

    <form method="get" class="mb-3">
        <label>Filter by Job Role:</label>
        <select name="job_role" onchange="this.form.submit()">
            <option value="">-- All Roles --</option>
            <?php foreach($roles as $role): ?>
                <option value="<?= htmlspecialchars($role) ?>" <?= ($filter_job_role==$role)?'selected':'' ?>><?= htmlspecialchars($role) ?></option>
            <?php endforeach; ?>
        </select>
    </form>

    <form method="post">
        <div class="form-group mb-3">
            <label>Company Name:</label>
            <input type="text" name="company_name" class="form-control" placeholder="Enter company name" required>
            <input type="hidden" name="job_role_notify" value="<?= htmlspecialchars($filter_job_role) ?>">
        </div>

        <table>
            <thead>
                <tr>
                    <th>Select</th>
                    <th>Student Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Skills</th>
                    <th>Preferred Job Role</th>
                    <th>Resume</th>
                </tr>
            </thead>
            <tbody>
                <?php while($student = $result->fetch_assoc()): ?>
                    <tr>
                        <td><input type="checkbox" name="students[]" value="<?= (int)$student['student_id'] ?>"></td>
                        <td><?= htmlspecialchars($student['full_name'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($student['email'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($student['phone'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($student['address'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($student['skills'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($student['job_role'] ?? '-') ?></td>
                        <td>
                            <?php if(!empty($student['resume_path'])): ?>
                                <a href="<?= htmlspecialchars($student['resume_path']) ?>" target="_blank">View</a>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <button type="submit" name="send_notification" class="btn btn-success mt-3">Send Notification to Selected Students</button>
        <div class="container mt-3 text-center">
            <a href="admin_dashboard.php" class="btn btn-primary back-btn">Back to Dashboard</a>
        </div>
    </form>
</div>
</body>
</html>

<?php $conn->close(); ?>

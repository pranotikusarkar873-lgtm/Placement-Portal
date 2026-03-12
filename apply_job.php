<?php
session_start();

if (!isset($_SESSION['student_id'])) {
    header("Location: student_portal.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['job_id'])) {
    $student_id = intval($_SESSION['student_id']);
    $job_id = intval($_POST['job_id']);

    $conn = new mysqli("localhost", "root", "", "placement_system");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if already applied
    $check = $conn->prepare("SELECT 1 FROM job_applications WHERE student_id = ? AND job_id = ?");
    $check->bind_param("ii", $student_id, $job_id);
    $check->execute();
    $check_result = $check->get_result();

    if ($check_result && $check_result->num_rows > 0) {
        header("Location: student_dashboard.php?msg=already_applied");
        exit();
    }

    // Insert application
    $stmt = $conn->prepare("INSERT INTO job_applications (student_id, job_id, applied_date) VALUES (?, ?, NOW())");
    $stmt->bind_param("ii", $student_id, $job_id);
    $stmt->execute();

    // Fetch company email and job info
    $job_query = $conn->prepare("
        SELECT c.email AS company_email, c.company_name, j.job_title 
        FROM jobs j 
        JOIN companies c ON j.company_id = c.id 
        WHERE j.id = ?
    ");
    $job_query->bind_param("i", $job_id);
    $job_query->execute();
    $job = $job_query->get_result()->fetch_assoc();

    // Fetch student name
    $student_query = $conn->prepare("SELECT full_name FROM students WHERE id = ?");
    $student_query->bind_param("i", $student_id);
    $student_query->execute();
    $student = $student_query->get_result()->fetch_assoc();
    $student_name = $student['full_name'];

    // Send email notification to company
    $to = $job['company_email'];
    $subject = "New Job Application: " . $job['job_title'];
    $message = "Dear " . $job['company_name'] . ",\n\n"
             . "Student '" . $student_name . "' has applied for the job: " . $job['job_title'] . ".\n\n"
             . "Regards,\nPlacement Cell";
    $headers = "From: placement@college.edu";

    // Optional: Uncomment to enable mail (requires mail server setup)
    // @mail($to, $subject, $message, $headers);

    $conn->close();
    header("Location: student_dashboard.php?msg=applied_success");
    exit();
}
?>

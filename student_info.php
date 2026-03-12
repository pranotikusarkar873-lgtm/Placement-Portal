<?php
session_start();
$conn = new mysqli("localhost", "root", "", "placement_system");
if (!isset($_SESSION['student_id'])) {
    header("Location: student_portal.php");
    exit();
}

// Fetch student details
$id = $_SESSION['student_id'];
$result = $conn->query("SELECT * FROM students WHERE id = $id");
$student = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Profile</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body { font-family: Arial; padding: 40px; background: #f4f4f4; }
        .info-card { max-width: 500px; margin: auto; padding: 30px; background: white; border-radius: 8px; box-shadow: 0 0 10px #ccc; }
        .info-card h2 { margin-bottom: 20px; }
        .info-card p { font-size: 18px; margin-bottom: 10px; }
        .logout-btn { display: inline-block; padding: 10px 15px; background: crimson; color: white; text-decoration: none; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="info-card">
        <h2>Welcome, <?= $student['name'] ?> 🎓</h2>
        <p><strong>Email:</strong> <?= $student['email'] ?></p>
        <p><strong>ID:</strong> <?= $student['id'] ?></p>
        <!-- Add more student info here -->
        <a class="logout-btn" href="student_portal.php?logout=true">Logout</a>
    </div>
</body>
</html>

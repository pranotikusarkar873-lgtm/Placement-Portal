<?php
session_start();
$conn = new mysqli("localhost", "root", "", "placement_system");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Only allow admin
if (!isset($_SESSION['admin_id'])) {
    die("Unauthorized access!");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];

    $target_dir = "uploads/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $photo_name = time() . "_" . basename($_FILES["photo"]["name"]);
    $target_file = $target_dir . $photo_name;

    if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
        $stmt = $conn->prepare("INSERT INTO photos (title, description, photo_path) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $title, $description, $photo_name);
        $stmt->execute();

        echo "<script>alert('Photo uploaded successfully!'); window.location='gallery.php';</script>";
    } else {
        echo "<script>alert('Error uploading photo.'); window.location='gallery.php';</script>";
    }
}
?>

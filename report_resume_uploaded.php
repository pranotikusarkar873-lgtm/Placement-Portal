<?php
session_start();
$conn = new mysqli("localhost", "root", "", "placement_system");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// If download button clicked
if (isset($_GET['download']) && $_GET['download'] == 'excel') {

    $sql = "
        SELECT 
            s.name AS student_name, 
            s.email, 
            p.resume_path,
            p.resume_uploaded_at
        FROM student_profiles p
        JOIN students s ON p.student_id = s.id
        WHERE p.resume_path IS NOT NULL 
          AND p.resume_path != ''
    ";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {

        $filename = "resume_uploaded_students_" . date('Y-m-d') . ".csv";

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen("php://output", "w");

        // Column headings
        fputcsv($output, ["Student Name", "Email", "Resume Path", "Uploaded Date"]);

        // Rows
        while ($row = $result->fetch_assoc()) {
            fputcsv($output, [
                $row['student_name'],
                $row['email'],
                $row['resume_path'],
                !empty($row['resume_uploaded_at'])
                    ? date("d M Y, h:i A", strtotime($row['resume_uploaded_at']))
                    : "Not Recorded"
            ]);
        }

        fclose($output);
        exit();
    } else {
        echo "No data found!";
        exit();
    }
}

// Normal page view
$sql = "
    SELECT 
        s.name AS student_name, 
        s.email, 
        p.resume_path,
        p.resume_uploaded_at
    FROM student_profiles p
    JOIN students s ON p.student_id = s.id
    WHERE p.resume_path IS NOT NULL 
      AND p.resume_path != ''
";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Resume Uploaded Students</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>

<body>
<div class="container mt-5">

    <div class="card p-4 shadow">

        <!-- Top Row (Title + Download Button) -->
        <div class="d-flex justify-content-between align-items-center">
            <h3>
                <i class="bi bi-upload text-primary"></i> 
                Students who Uploaded Resume
            </h3>

            <a href="?download=excel" class="btn btn-success">
                ⬇️ 
            </a>
        </div>

        <table class="table table-bordered mt-3">
            <thead class="table-dark">
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Resume</th>
                    <th>Uploaded Date</th>
                </tr>
            </thead>

            <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['student_name']); ?></td>
                    <td><?= htmlspecialchars($row['email']); ?></td>

                    <td>
                        <a href="<?= htmlspecialchars($row['resume_path']); ?>" 
                           target="_blank" 
                           class="btn btn-primary btn-sm">
                            View Resume
                        </a>
                    </td>

                    <td>
                        <?php 
                        if (!empty($row['resume_uploaded_at'])) {
                            echo htmlspecialchars(date("d M Y, h:i A", strtotime($row['resume_uploaded_at'])));
                        } else {
                            echo "<span class='text-danger'>Not Recorded</span>";
                        }
                        ?>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <div class="text-center mt-3">
        <a href="student_reports.php" class="btn btn-secondary">Back</a>
    </div>

</div>
</body>
</html>

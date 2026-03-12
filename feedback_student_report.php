<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "placement_system");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Keywords considered as company
$company_keywords = ["pvt", "private", "ltd", "solutions", "company", "inc"];

// -------------------- DOWNLOAD CSV --------------------
if (isset($_GET['download']) && $_GET['download'] == 'excel') {

    $sql = "SELECT date, name, comment FROM feedbacks ORDER BY date DESC";
    $result = $conn->query($sql);

    $filename = "student_feedback_report_" . date('Y-m-d') . ".csv";

    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '"');

    $output = fopen('php://output', 'w');

    // Column headers
    fputcsv($output, array('Date', 'Student Name', 'Feedback'));

    while ($row = $result->fetch_assoc()) {

        // Skip company names
        $is_company = false;
        foreach ($company_keywords as $kw) {
            if (stripos($row['name'], $kw) !== false) {
                $is_company = true;
                break;
            }
        }
        if ($is_company) continue;

        fputcsv($output, array(
            date("d-M-Y", strtotime($row['date'])),
            $row['name'],
            $row['comment']
        ));
    }

    fclose($output);
    exit();
}
// ------------------------------------------------------

// Normal view
$sql = "SELECT * FROM feedbacks ORDER BY date DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Feedback Report</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container-custom {
            max-width: 1000px;
            margin: 40px auto;
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        .table th {
            background-color: #007bff;
            color: white;
        }
    </style>
</head>

<body class="bg-light">

<div class="container-custom">
    <h2 class="text-center mb-4">Student Feedback Report</h2>

    <!-- DOWNLOAD BUTTON TOP RIGHT -->
    <div class="d-flex justify-content-end mb-3">
        <a href="?download=excel" class="btn btn-primary">⬇️</a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Student Name</th>
                    <th>Feedback</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $count = 0;
            while ($row = $result->fetch_assoc()) {

                // Skip company names
                $is_company = false;
                foreach ($company_keywords as $kw) {
                    if (stripos($row['name'], $kw) !== false) {
                        $is_company = true;
                        break;
                    }
                }
                if ($is_company) continue;

                $count++;
                ?>
                <tr>
                    <td><?= date("d-M-Y", strtotime($row['date'])); ?></td>
                    <td><?= htmlspecialchars($row['name']); ?></td>
                    <td><?= nl2br(htmlspecialchars($row['comment'])); ?></td>
                </tr>
            <?php } ?>

            <?php if ($count == 0): ?>
                <tr><td colspan="3" class="text-center">No student feedback found.</td></tr>
            <?php endif; ?>

            </tbody>
        </table>
    </div>

    <!-- BACK BUTTON -->

     <div class="text-center mt-3 mb-5">
        <a href="feedback_report.php" class="btn btn-primary">Back</a>
    </div>
    

</div>

</body>
</html>

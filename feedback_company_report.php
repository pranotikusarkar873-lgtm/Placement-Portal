<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "placement_system");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// DOWNLOAD EXCEL for Company Feedback
if (isset($_GET['download']) && $_GET['download'] == 'excel') {

    $sql = "SELECT date, name, comment FROM feedbacks WHERE user_type='Company' ORDER BY date DESC";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {

        $filename = "company_feedback_report_" . date('Y-m-d') . ".csv";

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');

        // Column headings
        fputcsv($output, array('Date', 'Company Name', 'Feedback'));

        while ($row = $result->fetch_assoc()) {
            fputcsv($output, array(
                date("d-M-Y", strtotime($row['date'])),
                $row['name'],
                $row['comment']
            ));
        }

        fclose($output);
        exit();
    } else {
        echo "No company feedback found!";
        exit();
    }
}

// Normal page load
$sql = "SELECT * FROM feedbacks WHERE user_type='Company' ORDER BY date DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Company Feedback Report</title>
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
            background-color: #28a745;
            color: white;
        }
    </style>
</head>

<body class="bg-light">

<div class="container-custom">
    <h2 class="text-center mb-4">Company Feedback Report</h2>

    <!-- DOWNLOAD BUTTON TOP RIGHT -->
    <div class="d-flex justify-content-end mb-3">
        <a href="?download=excel" class="btn btn-success">⬇️</a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Company Name</th>
                    <th>Feedback</th>
                </tr>
            </thead>
            <tbody>

            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= date("d-M-Y", strtotime($row['date'])); ?></td>
                    <td><?= htmlspecialchars($row['name']); ?></td>
                    <td><?= nl2br(htmlspecialchars($row['comment'])); ?></td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3" class="text-center">No company feedback found.</td>
                </tr>
            <?php endif; ?>

            </tbody>
        </table>
    </div>

    <div class="text-center">
        <a href="feedback_report.php" class="btn btn-success">Back</a>
    </div>
</div>

</body>
</html>

<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "placement_system");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$sql = "SELECT placement_date AS placement_year, COUNT(*) AS total
        FROM placed_students
        GROUP BY placement_date
        ORDER BY placement_date";

$result = $conn->query($sql);

$years = [];
$totals = [];
if($result){
    while($row = $result->fetch_assoc()){
        $years[] = $row['placement_year'];
        $totals[] = $row['total'];
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Year-wise Placement Comparison</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Fixed-size chart container */
        .chart-container {
            max-width: 600px;
            height: 400px; /* fixed height */
            margin: 0 auto;
        }

        /* Style the back button */
        .back-btn {
            background-color: #007bff;
            color: #fff;
            padding: 8px 20px;
            border-radius: 5px;
            text-decoration: none;
            transition: 0.3s;
        }

        .back-btn:hover {
            background-color: #0056b3;
            color: #fff;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <div class="card p-4 shadow text-center">
        <h3 class="mb-3">Year-wise Placement Comparison</h3>
        <div class="chart-container">
            <canvas id="placementChart"></canvas>
        </div>
    </div>

    <div class="text-center mt-3">
        <a href="placement_reports.php" class="back-btn">Back </a>
    </div>
</div>

<script>
const ctx = document.getElementById('placementChart').getContext('2d');
const placementChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?= json_encode($years); ?>,
        datasets: [{
            label: 'Number of Placements',
            data: <?= json_encode($totals); ?>,
            backgroundColor: 'rgba(54, 162, 235, 0.7)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                ticks: { stepSize: 1 }
            }
        }
    }
});
</script>
</body>
</html>

<?php $conn->close(); ?>

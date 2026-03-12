<?php
session_start();
if (!isset($_SESSION['company_id'])) {
    header("Location: company_portal.php");
    exit();
}

$company_name = $_SESSION['company_name'];

$conn = new mysqli("localhost", "root", "", "placement_system");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$sql = "SELECT job_title, job_description, location, posted_date 
        FROM jobs 
        WHERE company_name = '$company_name'
        ORDER BY posted_date DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Jobs Posted Report</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h3 class="text-primary mb-4">Jobs Posted by <?= htmlspecialchars($company_name); ?></h3>
    <?php if ($result && $result->num_rows > 0): ?>
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>Job Title</th>
                    <th>Description</th>
                    <th>Location</th>
                    <th>Posted Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['job_title']); ?></td>
                        <td><?= htmlspecialchars($row['job_description']); ?></td>
                        <td><?= htmlspecialchars($row['location']); ?></td>
                        <td><?= htmlspecialchars($row['posted_date']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-info">No jobs posted yet.</div>
    <?php endif; ?>
    <a href="company_dashboard.php" class="btn btn-primary mt-3">Back to Dashboard</a>
</div>
</body>
</html>
<?php $conn->close(); ?>

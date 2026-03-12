<?php
session_start();

// Optional: Add admin authentication check here

$conn = new mysqli("localhost", "root", "", "placement_system");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$from_date = $to_date = "";
$where_clause = "";

// Handle date filtering
if (isset($_GET['from_date']) && isset($_GET['to_date']) && $_GET['from_date'] !== "" && $_GET['to_date'] !== "") {
    $from_date = $_GET['from_date'];
    $to_date = $_GET['to_date'];
    $where_clause = "WHERE DATE(s.registered_at) BETWEEN '$from_date' AND '$to_date'";
}

// Fetch registered students with optional date filter
$sql = "
    SELECT s.id, s.name, s.email, s.registered_at, sp.phone, sp.address, sp.resume_path
    FROM students s
    LEFT JOIN student_profiles sp ON s.id = sp.student_id
    $where_clause
    ORDER BY s.id DESC
";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Placement Portal</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center mb-4 text-primary">Registered Students Report</h2>

    <!-- Date Filter Form -->
    <form method="get" class="form-inline mb-4 justify-content-center">
        <label class="mr-2">From:</label>
        <input type="date" name="from_date" class="form-control mr-3" value="<?= htmlspecialchars($from_date) ?>" required>
        <label class="mr-2">To:</label>
        <input type="date" name="to_date" class="form-control mr-3" value="<?= htmlspecialchars($to_date) ?>" required>
        <button type="submit" class="btn btn-primary">Filter</button>
        <a href="admin_registered_students.php" class="btn btn-secondary ml-2">Reset</a>
    </form>

    <?php if ($result && $result->num_rows > 0): ?>
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>#</th>
                    <th>Student Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Registered On</th>
                    <th>Resume</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1; while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $i++; ?></td>
                        <td><?= htmlspecialchars($row['name']); ?></td>
                        <td><?= htmlspecialchars($row['email']); ?></td>
                        <td><?= htmlspecialchars($row['phone'] ?? 'N/A'); ?></td>
                        <td><?= htmlspecialchars($row['address'] ?? 'N/A'); ?></td>
                        <td><?= htmlspecialchars(date('d-m-Y', strtotime($row['registered_at']))); ?></td>
                        <td>
                            <?php if (!empty($row['resume_path']) && file_exists($row['resume_path'])): ?>
                                <a href="<?= $row['resume_path']; ?>" target="_blank">View Resume</a>
                            <?php elseif (!empty($row['resume_path'])): ?>
                                <span class="text-danger">Missing File</span>
                            <?php else: ?>
                                N/A
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-info text-center">No students found for the selected date range.</div>
    <?php endif; ?>
</div>
<div class="text-center">
        <a href="admin_dashboard.php" class="btn btn-primary back-btn"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
    </div>
    
</body>
</html>

<?php $conn->close(); ?>

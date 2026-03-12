<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin.php");
    exit();
}

// Database connection
$conn = new mysqli("localhost", "root", "", "placement_system");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Export to Excel
if (isset($_POST['export_excel'])) {
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=pending_companies.xls");
    
    echo "ID\tName\tEmail\tContact\tRegistered At\n";
    
    $sql = "SELECT * FROM companies WHERE status='pending' ORDER BY registered_at DESC";
    $result = $conn->query($sql);
    
    while ($row = $result->fetch_assoc()) {
        echo $row['id'] . "\t" . $row['name'] . "\t" . $row['email'] . "\t" . $row['contact'] . "\t" . $row['registered_at'] . "\n";
    }
    exit();
}

// Fetch pending companies
$sql = "SELECT * FROM companies WHERE status='pending' ORDER BY registered_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pending Companies - Placement Portal</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow p-4 mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3><i class="fas fa-hourglass-half text-warning"></i> Pending Companies</h3>
            <form method="post" class="m-0">
                <button type="submit" name="export_excel" class="btn btn-warning">
                    ⬇️
                </button>
            </form>
        </div>
        <p>List of all companies pending admin approval.</p>
    </div>

    <div class="card shadow p-4">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                  
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td><?= htmlspecialchars($row['email']) ?></td>
                            
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="5" class="text-center">No pending companies found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="text-center mt-3 mb-5">
        <a href="company_reports.php" class="btn btn-primary">Back</a>
    </div>
</div>
</body>
</html>

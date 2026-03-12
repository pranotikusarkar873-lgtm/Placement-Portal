<?php
$conn = new mysqli("localhost", "root", "", "placement_db");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$sql = "SELECT company, DATE_FORMAT(drive_date, '%Y-%m-%d') as date FROM drives WHERE drive_date >= CURDATE() ORDER BY drive_date ASC";
$result = $conn->query($sql);

$drives = [];
while($row = $result->fetch_assoc()) $drives[] = $row;

header('Content-Type: application/json');
echo json_encode($drives);
?>

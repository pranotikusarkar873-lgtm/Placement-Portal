<?php
$conn = new mysqli("localhost", "root", "", "placement_db");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$sql = "SELECT title, company, DATE_FORMAT(posted_on, '%d %b %Y') as posted_on FROM jobs ORDER BY posted_on DESC LIMIT 6";
$result = $conn->query($sql);

$jobs = [];
while($row = $result->fetch_assoc()) $jobs[] = $row;

header('Content-Type: application/json');
echo json_encode($jobs);
?>

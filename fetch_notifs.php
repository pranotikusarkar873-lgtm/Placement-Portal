<?php
$conn = new mysqli("localhost", "root", "", "placement_db");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$sql = "SELECT message AS msg, DATE_FORMAT(notif_date, '%d %b %Y') AS date FROM notifications ORDER BY notif_date DESC LIMIT 5";
$result = $conn->query($sql);

$notifs = [];
while($row = $result->fetch_assoc()) $notifs[] = $row;

header('Content-Type: application/json');
echo json_encode($notifs);
?>

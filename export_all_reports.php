<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin.php");
    exit();
}

// Include PhpSpreadsheet
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Database connection
$conn = new mysqli("localhost", "root", "", "placement_system");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Create spreadsheet
$spreadsheet = new Spreadsheet();

// --------------------
// 1. Student Reports
// --------------------
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('All Students');
$sheet->fromArray(['ID','Name','Email','Phone','Resume','Registered At'], NULL, 'A1');

$sql = "SELECT id, name, email, phone, resume, registered_at FROM students ORDER BY id";
$result = $conn->query($sql);
$rowNum = 2;
while($row = $result->fetch_assoc()){
    $sheet->fromArray(array_values($row), NULL, 'A'.$rowNum);
    $rowNum++;
}

// --------------------
// 2. Placed Students
// --------------------
$placedSheet = $spreadsheet->createSheet();
$placedSheet->setTitle('Placed Students');
$placedSheet->fromArray(['ID','Student Name','Company Name','Job Role','Placement Year','Package'], NULL, 'A1');

$sql2 = "SELECT id, student_name, company_name, job_role, placement_date, package FROM placed_students ORDER BY id";
$result2 = $conn->query($sql2);
$rowNum = 2;
while($row = $result2->fetch_assoc()){
    $row['placement_date'] = date('Y', strtotime($row['placement_date']));
    $placedSheet->fromArray(array_values($row), NULL, 'A'.$rowNum);
    $rowNum++;
}

// --------------------
// 3. Company Reports
// --------------------
$companySheet = $spreadsheet->createSheet();
$companySheet->setTitle('Companies');
$companySheet->fromArray(['ID','Company Name','Email','Phone','Status'], NULL, 'A1');

$sql3 = "SELECT id, company_name, email, phone, status FROM companies ORDER BY id";
$result3 = $conn->query($sql3);
$rowNum = 2;
while($row = $result3->fetch_assoc()){
    $companySheet->fromArray(array_values($row), NULL, 'A'.$rowNum);
    $rowNum++;
}

// --------------------
// 4. Feedback Reports (Student & Company)
// --------------------
$feedbackSheet = $spreadsheet->createSheet();
$feedbackSheet->setTitle('Student Feedback');
$feedbackSheet->fromArray(['ID','Student Name','Feedback','Created At'], NULL, 'A1');

$sql4 = "SELECT id, student_name, feedback, created_at FROM feedback_student ORDER BY id";
$result4 = $conn->query($sql4);
$rowNum = 2;
while($row = $result4->fetch_assoc()){
    $feedbackSheet->fromArray(array_values($row), NULL, 'A'.$rowNum);
    $rowNum++;
}

$companyFeedbackSheet = $spreadsheet->createSheet();
$companyFeedbackSheet->setTitle('Company Feedback');
$companyFeedbackSheet->fromArray(['ID','Company Name','Feedback','Created At'], NULL, 'A1');

$sql5 = "SELECT id, company_name, feedback, created_at FROM feedback_company ORDER BY id";
$result5 = $conn->query($sql5);
$rowNum = 2;
while($row = $result5->fetch_assoc()){
    $companyFeedbackSheet->fromArray(array_values($row), NULL, 'A'.$rowNum);
    $rowNum++;
}

// --------------------
// Output Excel file
// --------------------
$filename = "all_reports_" . date('Y-m-d_H-i-s') . ".xlsx";
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="'.$filename.'"');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');

$conn->close();
exit();
?>

<?php
include ('connect.php');

$receipt_no = $_POST['receipt_no'];
$fee_type = $_POST['fee_type'];
$amount = $_POST['amount'];
$date = $_POST['date'];
$student_id = $_POST['student-id'];

$sql = "INSERT INTO Payment (receipt_no, fee_type, amount, date, student_id) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssi", $receipt_no, $fee_type, $amount, $date, $student_id);
$stmt->execute();
$stmt->close();
header("Location:  ../index.php");
?>
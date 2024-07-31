<?php
include('connect.php');

$id = $_POST['id'];
$receipt_no = $_POST['receipt_no'];
$fee_type = $_POST['fee_type'];
$amount = $_POST['amount'];
$date = $_POST['date'];
$student_id = $_POST['student_id'];

$sql = "UPDATE Payment SET receipt_no=?, fee_type=?, amount=?, date=?, student_id=? WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssiii", $receipt_no, $fee_type, $amount, $date, $student_id, $id);
$stmt->execute();
$stmt->close();
?>

<?php
include('connect.php');

$id = $_POST['id'];

$sql = "SELECT Payment.*, Student.student_name FROM Payment JOIN Student ON Payment.student_id = Student.id WHERE Payment.id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo "<h5>Receipt No: " . $row['receipt_no'] . "</h5>";
    echo "<p>Student Name: " . $row['student_name'] . "</p>";
    echo "<p>Fee Type: " . $row['fee_type'] . "</p>";
    echo "<p>Amount: " . $row['amount'] . "</p>";
    echo "<p>Date: " . $row['date'] . "</p>";
} else {
    echo "No details found.";
}

$stmt->close();
?>

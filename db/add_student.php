<?php
include('connect.php');

$admission_no = $_POST['admission_no'];
$student_name = $_POST['student_name'];
$father_name = $_POST['father_name'];
$grade = $_POST['grade'];
$class = $_POST['class'];
$contact_no = $_POST['contact_no'];

$sql = "INSERT INTO Student (admission_no, student_name, father_name, grade, class, contact_no) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssss", $admission_no, $student_name, $father_name, $grade, $class, $contact_no);

if ($stmt->execute()) {
    echo "Success";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>

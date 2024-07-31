<?php
include('connect.php');


$id = $_POST['id'];

$sql = "DELETE FROM Student WHERE id=$id";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo "Success";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>

<?php
include('connect.php');

$id = $_POST['id'];

$sql = "DELETE FROM Payment WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();
header("Location:  ../index.php");
?>

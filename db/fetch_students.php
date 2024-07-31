<?php
include('connect.php');

if (isset($_GET['admission_no'])) {
    $admission_no = $_GET['admission_no'];

    // SQL query to fetch students with matching admission numbers
    $sql = "SELECT * FROM Student WHERE admission_no LIKE ?";
    $stmt = $conn->prepare($sql);
    $search_param = $admission_no . '%';
    $stmt->bind_param("s", $search_param);
    $stmt->execute();
    $result = $stmt->get_result();

    $students = array();
    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }

    echo json_encode($students);
}
?>

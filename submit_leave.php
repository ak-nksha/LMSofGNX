<?php
session_start();
$conn = new mysqli("localhost", "root", "", "leave_db");
$userid = $_POST['userid'];
$leave_type = $_POST['leave_type'];
$reason = $_POST['reason'];
$for_date = $_POST['for_date'];


$stmt = $conn->prepare("INSERT INTO leave_requests (userid, leave_type, reason, for_date) VALUES (?, ?, ?, ?)");


$stmt->bind_param("ssss", $userid, $leave_type, $reason, $for_date);
$stmt->execute();

echo "<script>
            window.location='" . $_SESSION['position'] . "_dash.php';
            </script>";
?>

<?php
session_start();
$conn = new mysqli("localhost", "root", "", "leave_db");
$id = $_GET['id'];
$status = $_GET['status'];
$reviewed_by = $_SESSION['userid'];
$stmt = $conn->prepare("UPDATE leave_requests SET status = ?, reviewed_by = ? WHERE id = ?");
$stmt->bind_param("ssi", $status, $reviewed_by, $id);
$stmt->execute();
echo "<script>window.location='manage_leaves.php';</script>";
?>

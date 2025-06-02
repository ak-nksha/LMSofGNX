<?php
session_start();
$conn = new mysqli("localhost", "root", "", "leave_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$userid = $_POST['userid'];
$password = $_POST['password'];
$position = $_POST['position'];


$query = $conn->prepare("SELECT * FROM users WHERE userid = ? AND password = ? AND position = ?");

$query->bind_param("sss", $userid, $password, $position);

$query->execute();

$result = $query->get_result();
if ($result->num_rows === 1) {
    $_SESSION['userid'] = $userid;
    $_SESSION['position'] = $position;
    if ($position === "admin") header("Location: admin_dash.php");
    elseif ($position === "manager") header("Location: manager_dash.php");
    else header("Location: employee_dash.php");
    exit();
} else {
    echo "<script>alert('Invalid credentials!'); window.location='index.php';</script>";
}
?>
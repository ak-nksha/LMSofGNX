<?php
session_start();
if (!isset($_SESSION['userid']) || $_SESSION['position'] !== 'employee') {
    header("Location: index.php");
    exit();
}
$conn = new mysqli("localhost", "root", "", "leave_db");

$userid = $_SESSION['userid'];

$result = $conn->query("SELECT * FROM leave_requests WHERE userid = '$userid' ORDER BY created_at DESC");
 ?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Employee Dashboard</title>
   
</head>
<body>
<div class="dash">
    <header>
        <img src="img/logo.png" class="logo">
        <h1>Employee Dashboard</h1>
        <h2>Welcome, <?php echo $userid; ?> (Employee)</h2>
        <button class="logout" onclick="logout()">Logout</button>
    </header>
    <div class="container">
        <div class="group">
            <div class="panel" style="width:100%">
                <h2>Employees on Leave Today</h2>
                <button onclick="todays_leaves()">Today's Approved Leaves</button>
            </div>
        
            <div class="panel" style="width:100%">
                <h2>Apply for Leave</h2>
                <button onclick="requestLeave()">Request Leave</button>
            </div>
        </div>
        <div class="panel">
            <h2>Leave History</h2>
            <table>
                <tr><th>Date</th><th>Type</th><th>Reason</th><th>Status</th><th>Reviewed By</th></tr>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['for_date']; ?></td>
                        <td><?php echo $row['leave_type']; ?></td>
                        <td><?php echo $row['reason']; ?></td>
                        <td><?php echo $row['status']; ?></td>
                        <td><?php echo $row['reviewed_by']; ?></td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>
</div>
    <script>
        function logout() {
            window.location.href = "index.php"
        }
        function requestLeave() {
            window.location.href = "leave_apply.php"
        }
        
        function todays_leaves(){
            window.location.href = "todays_leaves.php"
        }
    </script>
</body>
</html>

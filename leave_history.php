<?php
session_start();
$conn = new mysqli("localhost", "root", "", "leave_db");

$userid = $_SESSION['userid'];

$result = $conn->query("SELECT * FROM leave_requests WHERE userid = '$userid' ORDER BY created_at DESC");

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Leave Requests List</title>
   
</head>
<body>
<div class="dash">
    <header>
        <img src="img/logo.png" class="logo">
        <h2>Leave History</h2>
        <p onclick="back()">  
            <u>Back</u> 
        </p>
        
    </header>

    <div class="panel">
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




<script>
    function back(){
        window.history.back(); 
        }
</script>

</body>
</html>
<?php
session_start();
$conn = new mysqli("localhost", "root", "", "leave_db");
$date = date('Y-m-d');
$result = $conn->query("SELECT * FROM leave_requests WHERE for_date = '$date' AND status = 'approved'");
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
        <h2>Today's Approved Leaves (<?php echo $date; ?>)</h2>
        <p onclick="back()">  
            <u>Back</u> 
        </p>
        
    </header>

    <div class="panel">
        
        <h2>Today's Approved Leaves (<?php echo $date; ?>)</h2>
        <table>
            <tr><th>User ID</th><th>Type</th><th>Reason</th></tr>
            <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
            <td><?php echo $row['userid']; ?></td>
            <td><?php echo $row['leave_type']; ?></td>
            <td><?php echo $row['reason']; ?></td>
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
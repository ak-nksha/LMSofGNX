<?php
session_start();
$conn = new mysqli("localhost", "root", "", "leave_db");
$requests = $conn->query("SELECT * FROM leave_requests WHERE status='pending' ORDER BY created_at DESC");
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
        <h2>Manage Leaves</h2>
        <p onclick="back()">  
            <u>Back</u> 
        </p>
        
    </header>

    <div class="panel">
        
        <h2>Manage Leave Requests</h2>
        <table>
            <tr><th>User ID</th><th>For Date</th><th>Type</th><th>Reason</th><th>Status</th><th>Reviewed By</th><th>Action</th></tr>
            <?php while ($row = $requests->fetch_assoc()) { ?>
            <tr>
            <td><?php echo $row['userid']; ?></td>
            <td><?php echo $row['for_date']; ?></td>
            <td><?php echo $row['leave_type']; ?></td>
            <td><?php echo $row['reason']; ?></td>
            <td><?php echo $row['status']; ?></td>
            <td><?php echo $row['reviewed_by']; ?></td>
            <td>
                <?php if ($row['status'] == 'pending') { ?>
                    <a href="update_status.php?id=<?php echo $row['id']; ?>&status=approved">Approve</a> |
                    <a href="update_status.php?id=<?php echo $row['id']; ?>&status=rejected">Reject</a>
                <?php } else { echo $row['status']; } ?>
            </td>
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
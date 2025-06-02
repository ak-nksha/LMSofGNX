<?php
session_start();
if (!isset($_SESSION['userid'])) {
    header("Location: index.php");
    exit();
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Requestleave    </title>
</head>

<body>
    <div class="login-container">
        <img src="img/logo.png" class="logo" style="width:40%">
        <h1 > Request Leave </h1>
        <p onclick="back()" style="padding-bottom: 5%;">  
            <u>Back</u> 
        </p>
        <form action="submit_leave.php" method="POST">
            <input type="hidden" name="userid" value="<?php echo $_SESSION['userid']; ?>">
            <label>Type of Leave:</label>
            <select id="leaveType" name="leave_type" required>
                <option value="Casual Leave">Casual Leave</option>
                <option value="Sick Leave">Sick Leave</option>
                <option value="Half-Day Leave">Half-Day Leave</option>
            </select>
            <label>Reason:</label>
            <textarea name="reason" required></textarea>
            <label>For Date:</label>
            <input type="date" name="for_date" required>
            <button type="submit">Submit Leave Request</button>
        </form> 

    </div>


    <script>
        function back(){
            window.history.back(); 
        }
    </script>
</body>
</html>

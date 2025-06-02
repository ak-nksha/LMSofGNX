<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="login-container">
        <form action="login_process.php" method="POST">
            <img src="img/logo.png" class="logo" style="width:50%">
            <h1>Login</h1>
            <h2 style="font-size:140%">Employee Leave Management System</h2>
            <input type="text" name="userid" placeholder="User ID" required>
            <input type="password" name="password" placeholder="Password" required>
            <div class="radio-group">
                <label><input type="radio" name="position" value="admin" required> Admin</label>
                <label><input type="radio" name="position" value="manager" required> Manager</label>
                <label><input type="radio" name="position" value="employee" required> Employee</label>
            </div>
            <button class="B1" type="submit">Login</button>
            <p>If you forget the username or password, go to the administration.</p>
        </form>
    </div>
</body>
</html>
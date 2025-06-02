<?php
session_start();
if (!isset($_SESSION['userid']) || $_SESSION['position'] !== 'admin') {
    header("Location: index.php");
    exit();
}
$current_userid = $_SESSION['userid'];

$conn = new mysqli("localhost", "root", "", "leave_db");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Add or update user
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userid = $_POST['userid'];
    $password = $_POST['password'];
    $position = $_POST['position'];

    if (isset($_POST['update_id'])) {
        $update_id = $_POST['update_id'];
        $stmt = $conn->prepare("UPDATE users SET userid=?, password=?, position=? WHERE id=?");
        $stmt->bind_param("sssi", $userid, $password, $position, $update_id);
        $stmt->execute();
    } else {
        $stmt = $conn->prepare("INSERT INTO users (userid, password, position) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $userid, $password, $position);
        $stmt->execute();
    }
    header("Location: admin_dash.php");
    exit();
}

// Delete user
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM users WHERE id=?");
    $stmt = $conn->prepare("DELETE FROM leave_requests WHERE id=?");

    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: admin_dash.php");
    exit();
}

// Edit user
$edit_user = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM users WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $edit_user = $stmt->get_result()->fetch_assoc();
}

// History user
$history_user = null;
if (isset($_GET['history'])) {
    $userid = $_GET['history'];
    $result = $conn->query("SELECT * FROM leave_requests WHERE userid = '$userid' ORDER BY created_at DESC");
    $stmt = $conn->prepare("SELECT * FROM users WHERE userid=?");
    $stmt->bind_param("i", $userid);
    $stmt->execute();
    $history_user = $stmt->get_result()->fetch_assoc();
}

// Leave Requests from user
$leaveRequests = null;
if (isset($_GET['levReq'])) {
    $userid = $_GET['levReq'];
    $result = $conn->query("SELECT * FROM leave_requests ORDER BY created_at DESC");
    $stmt = $conn->prepare("SELECT * FROM users WHERE userid=?");
    $stmt->bind_param("i", $userid);
    $stmt->execute();
    $leaveRequests = $stmt->get_result()->fetch_assoc();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="dash">
    <header>
        <img src="img/logo.png" class="logo">
        <h1>Admin Dashboard</h1>
        <h2>Welcome, <?php echo $current_userid; ?> (Admin)</h2>
        <button class="logout" onclick="logout()">Logout</button>
    </header>

    <div class="container">
        <div class="panel">
            <h2>Manage Leaves</h2>
            <button onclick="todays_leaves()">Today's Approved Leaves</button>
            <button onclick="manage_leaves()">Leave Requests</button>
            <form method='get' style='display:inline;'>
                <input type='hidden' name='levReq' value='{$row['id']}'>
                <button type='submit'>Leave history of all users</button>
            </form>
        </div>

        <?php if ($leaveRequests): ?>
            <div class="panel">
                <h2>Leave Requests of the Users</h2>
                <p onclick="back()">  <u>Back</u> </p>
                <table>
                    <tr><th>UserId</th><th>For Date</th><th>Type</th><th>Reason</th><th>Status</th><th>Reviewed By</th></tr>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $row['userid']; ?></td>
                            <td><?php echo $row['for_date']; ?></td>
                            <td><?php echo $row['leave_type']; ?></td>
                            <td><?php echo $row['reason']; ?></td>
                            <td><?php echo $row['status']; ?></td>
                            <td><?php echo $row['reviewed_by']; ?></td>
                        </tr>
                    <?php } ?>
                </table>
            </div>
        <?php endif; ?>

        <?php if ($history_user): ?>
            <div class="panel">
                <h2>Leave history of <?= $history_user['userid'] ?></h2>
                <p onclick="back()">  <u>Back</u> </p>
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
        <?php endif; ?>
        
        <div class="group">
            <div class="panel">
                <h2>Add User</h2>
                <form method="POST">
                    <input type="text" name="userid" placeholder="User ID" required>
                    <input type="text" name="password" placeholder="Password" required>
                    <select name="position" required>
                        <option value="">Select Position</option>
                        <option value="admin">Admin</option>
                        <option value="manager">Manager</option>
                        <option value="employee">Employee</option>
                    </select>
                    <button class="B1" type="submit">Add User</button>
                </form>
            </div>

            <?php if ($edit_user): ?>
            <div class="panel">
                <h2>Edit User</h2>
                <p onclick="back()">  <u> back </u> </p>
                <form method="POST">
                    <input type="hidden" name="update_id" value="<?= $edit_user['id'] ?>">
                    <input type="text" name="userid" placeholder="User ID" required value="<?= $edit_user['userid'] ?>">
                    <input type="text" name="password" placeholder="Password" required value="<?= $edit_user['password'] ?>">
                    <select name="position" required>
                        <option value="">Select Position</option>
                        <option value="admin" <?= $edit_user['position'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                        <option value="manager" <?= $edit_user['position'] == 'manager' ? 'selected' : '' ?>>Manager</option>
                        <option value="employee" <?= $edit_user['position'] == 'employee' ? 'selected' : '' ?>>Employee</option>
                    </select>
                    <button class="B1" type="submit">Update User</button>
                </form>
            </div>
            <?php endif; ?>


        </div>
        <div class="panel">
            <h2>All Users</h2>
            <table>
                <tr>
                    <th>User ID</th><th>Password</th><th>Position</th><th>Actions</th>
                </tr>
                <?php
                $result = $conn->query("SELECT * FROM users");
                while ($row = $result->fetch_assoc()) {
                    $masked_password = str_repeat('*', strlen($row['password']));
                    echo "<tr>
                            <td>{$row['userid']}</td>
                            <td>
                                <span id='pass-{$row['id']}' class='password-mask'>{$masked_password}</span>
                                <button class='show-btn' onclick=\"togglePassword({$row['id']}, '{$row['password']}')\">Ö</button>
                            </td>
                            <td>{$row['position']}</td>
                            <td>
                                <a href='?history={$row['userid']}'>History</a> |
                                <a href='?edit={$row['id']}'>Edit</a> |
                                <a href='?delete={$row['id']}' onclick=\"return confirm('Delete this user?');\">Delete</a>
                            </td>
                        </tr>";
                }
                ?>
            </table>
        </div>
    </div>
</div>

<script>
function logout() {
    window.location.href = "index.php";
}

function back(){
        window.history.back(); 
        }

function todays_leaves(){
    window.location.href = "todays_leaves.php";
}

function manage_leaves(){
        window.location.href = "manage_leaves.php"
    }

function togglePassword(id, realPassword) {
    let span = document.getElementById('pass-' + id);
    if (span.innerText.includes('*')) {
        span.innerText = realPassword;
    } else {
        span.innerText = '*'.repeat(realPassword.length);
    }
}
</script>
</body>
</html>

<?php
$conn = new mysqli("localhost", "root", "", "leave_db");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

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
    header("Location: manage_users.php");
    exit();
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM users WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: manage_users.php");
    exit();
}

$edit_user = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM users WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $edit_user = $stmt->get_result()->fetch_assoc();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Users</title>
</head>
<body>
    <h2><?php echo $edit_user ? 'Edit' : 'Add'; ?> User</h2>
    <form method="POST">
        <input type="text" name="userid" placeholder="User ID" required value="<?= $edit_user['userid'] ?? '' ?>">
        <input type="text" name="password" placeholder="Password" required value="<?= $edit_user['password'] ?? '' ?>">
        <select name="position" required>
            <option value="">Select Position</option>
            <option value="admin" <?= (isset($edit_user) && $edit_user['position'] == 'admin') ? 'selected' : '' ?>>Admin</option>
            <option value="manager" <?= (isset($edit_user) && $edit_user['position'] == 'manager') ? 'selected' : '' ?>>Manager</option>
            <option value="employee" <?= (isset($edit_user) && $edit_user['position'] == 'employee') ? 'selected' : '' ?>>Employee</option>
        </select>
        <?php if ($edit_user): ?>
            <input type="hidden" name="update_id" value="<?= $edit_user['id'] ?>">
        <?php endif; ?>
        <button type="submit"><?= $edit_user ? 'Update' : 'Add' ?></button>
    </form>

    <h2>All Users</h2>
    <table>
        <tr>
            <th>ID</th><th>User ID</th><th>Password</th><th>Position</th><th>Actions</th>
        </tr>
        <?php
        $result = $conn->query("SELECT * FROM users");
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['userid']}</td>
                    <td>{$row['password']}</td>
                    <td>{$row['position']}</td>
                    <td>
                        <a href='?edit={$row['id']}'>Edit</a>
                        <a href='?delete={$row['id']}' onclick=\"return confirm('Delete this user?');\">Delete</a>
                    </td>
                </tr>";
        }
        ?>
    </table>
</body>
</html>

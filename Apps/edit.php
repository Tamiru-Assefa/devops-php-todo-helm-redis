<?php
include 'db.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['task'])) {
        $task = $conn->real_escape_string($_POST['task']);
        $status = $conn->real_escape_string($_POST['status']);
        $conn->query("UPDATE tasks SET task='$task', status='$status' WHERE id=$id");
        if ($redis) {
            $redis->del('tasks');
        }
        header('Location: index.php');
        exit;
    } else {
        $result = $conn->query("SELECT * FROM tasks WHERE id=$id");
        $row = $result->fetch_assoc();
    }
} else {
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Task</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="container">
    <h1>Edit Task</h1>
    <form action="" method="post">
        <input type="text" name="task" value="<?php echo htmlspecialchars($row['task']); ?>" required>
        <select name="status">
            <option value="Under Progress" <?php if($row['status']=='Under Progress') echo 'selected'; ?>>Under Progress</option>
            <option value="Completed" <?php if($row['status']=='Completed') echo 'selected'; ?>>Completed</option>
            <option value="Paused" <?php if($row['status']=='Paused') echo 'selected'; ?>>Paused</option>
        </select>
        <button type="submit">Save</button>
    </form>
    <a href="index.php">Cancel</a>
</div>
</body>
</html>
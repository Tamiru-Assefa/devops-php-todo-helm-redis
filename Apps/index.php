<?php

include 'db.php';
include 'redis.php';

$cacheKey = 'tasks';

$cachedTasks = $redis ? $redis->get($cacheKey) : false;

if ($cachedTasks !== false) {

    // Cache HIT
    $tasks = json_decode($cachedTasks, true);

} else {

    // Cache MISS
    $result = $conn->query("SELECT * FROM tasks ORDER BY id DESC");

    $tasks = [];

    while ($row = $result->fetch_assoc()) {
        $tasks[] = $row;
    }

    // Store tasks in Redis for 60 seconds
    if ($redis) {
        $redis->setex($cacheKey, 60, json_encode($tasks));
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Professional To-Do App with Status</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<div class="container">
    <h1>🌟 Task Manager</h1>

    <form action="add.php" method="post">
        <input type="text" name="task" placeholder="Add a new task..." required>
        <select name="status">
            <option value="Under Progress">Under Progress</option>
            <option value="Completed">Completed</option>
            <option value="Paused">Paused</option>
        </select>
        <button type="submit"><i class="fa fa-plus"></i> Add</button>
    </form>

    <table>
        <tr>
            <th>Task</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($tasks as $row): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['task']); ?></td>
            <td>
                <span class="status <?php echo strtolower(str_replace(' ', '-', $row['status'])); ?>">
                    <?php echo $row['status']; ?>
                </span>
            </td>
            <td>
                <a href="edit.php?id=<?php echo $row['id']; ?>"><i class="fa fa-edit"></i> Edit</a>
                <a href="delete.php?id=<?php echo $row['id']; ?>" style="color:#ef4444;"><i class="fa fa-trash"></i> Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>
</body>
</html>
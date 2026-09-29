<?php

include 'db.php';
include 'redis.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['task'])) {

    $task = $conn->real_escape_string($_POST['task']);
    $status = $conn->real_escape_string($_POST['status']);

    $conn->query(
        "INSERT INTO tasks (task, status) VALUES ('$task', '$status')"
    );

    // Remove old cached task list
    if ($redis) {
        $redis->del('tasks');
    }
}

header('Location: index.php');
exit;
?>
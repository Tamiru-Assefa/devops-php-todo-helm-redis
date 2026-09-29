<?php

include 'db.php';
include 'redis.php';

if (isset($_GET['id'])) {

    $id = intval($_GET['id']);

    $conn->query("DELETE FROM tasks WHERE id=$id");

    // Remove old cached task list
    if ($redis) {
        $redis->del('tasks');
    }
}

header('Location: index.php');
exit;
?>
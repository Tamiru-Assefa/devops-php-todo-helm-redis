<?php
$host = getenv('DB_HOST') ?: 'db';
$user = getenv('MYSQL_USER') ?: 'root';
$pass = getenv('MYSQL_PASSWORD') ?: '';
$db   = getenv('MYSQL_DATABASE') ?: 'taskdb';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
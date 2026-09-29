<?php

$redisHost = getenv('REDIS_HOST') ?: 'redis';
$redisPort = getenv('REDIS_PORT') ?: 6379;

$redis = new Redis();

try {
    $redis->connect($redisHost, $redisPort);
} catch (Exception $e) {
    $redis = null;
}
?>
<?php
require __DIR__.'/../Manager/QueueManager.php';
if ((!isset($argv[1]) || empty($argv[1])) ||
    (!isset($argv[2]) || empty($argv[2])))  {
    echo 'call this script using the msg and queueName as a parameter';
    exit;
}

$msg = $argv[1];
$queueName = $argv[2];

$consumer = new QueueManager();
$consumer->sendQueue($msg,$queueName);
exit(0);

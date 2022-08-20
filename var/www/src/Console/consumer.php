<?php
require __DIR__.'/../Manager/QueueManager.php';
if (!isset($argv[1]) || empty($argv[1]))  {
    echo 'call this script using the msg and queueName as a parameter';
    exit;
}
$queueName = $argv[1];

$consumer = new QueueManager();
$result = $consumer->consumerQueue($queueName);

print $result;


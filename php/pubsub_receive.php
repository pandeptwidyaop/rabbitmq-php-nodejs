<?php
require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;

$sName = $argv[1] ?: 'app';

$connection = new AMQPStreamConnection('localhost', 5672, 'VDg4E2VmLiixTctaC8gd19cgxICI0nEq', 'RD1Hp1qjGUVTdOqmW2ZYPVXrDRIsnJE2');
$channel = $connection->channel();
$channel->exchange_declare('GLOBAL_X', 'topic', false, false, false);
$channel->queue_declare($sName, false, false, false, false);
$routingKeys = ['user.created'];
foreach ($routingKeys as $key) {
    $channel->queue_bind($sName, 'GLOBAL_X', $key);
}
echo " [*] Waiting for logs on $sName. To exit press CTRL+C\n";

$callback = function ($msg) {
    echo ' [x] ', $msg->body, "\n";
    $msg->ack();
};

$channel->basic_consume($sName, '', false, false, false, false, $callback);

while ($channel->is_open()) {
    $channel->wait();
}

$channel->close();
$connection->close();

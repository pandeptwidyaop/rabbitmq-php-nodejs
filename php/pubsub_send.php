<?php

require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$to = $argv[1] ?: '';
$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();

$channel->exchange_declare('GLOBAL_X', 'topic', false, false, false);

for ($i = 0; $i < 1000; $i++) {
    $msg = new AMQPMessage("[" . time() . "] Hallo Semua $i");
    $channel->basic_publish($msg, 'GLOBAL_X', $to);
}

$channel->close();
$connection->close();

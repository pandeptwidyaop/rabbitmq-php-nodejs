<?php

require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;


$time = time();

for ($i = 0; $i <= 1000; $i++) {
    $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
    $channel = $connection->channel();
    $channel->queue_declare('hello', false, false, false, false);
    $msg = new AMQPMessage("[$time] Hello World! " . $i);
    $channel->basic_publish($msg, '', 'hello');
}


echo " [x] Sent 'Hello World!'\n";

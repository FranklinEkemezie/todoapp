<?php

header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
header('Connection: keep-alive');

$data = array(
  "message" => "Event 1"
);
$data = json_encode($data);

echo "id: 234er \ntype: update\ndata: $data\n\n";

sleep(2);

flush();

header('Connection: close');
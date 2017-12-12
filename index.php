<?php

require( './wechat_head.php' );

$server = $app->server;
$response = $server->serve();
$response->send();


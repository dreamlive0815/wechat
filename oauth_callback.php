<?php

require( './autoload.php' );

require( './wechat_head.php' );

$oauth = $app->oauth;
$user = $oauth->user();

print_r( $user );
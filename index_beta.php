<?php

$config = require( 'config_beta.php' );

require( $baseDir . '/vendor/autoload.php' );
use EasyWeChat\Foundation\Application;

$app = new Application( $config );
$server = $app->server;
$message = $server->getMessage();
if( $config['debug'] ) error_log( print_r( $message, 1 ), 3, './debug/access_beta.log' ) ;
$response = $server->serve();
$response->send();


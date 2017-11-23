<?php

$config = require( 'config_beta.php' );

include $baseDir . '/vendor/autoload.php';

use EasyWeChat\Foundation\Application;
use EasyWeChat\Message\Text;

$app = new Application( $config );
$staff = $app->staff;
$openid = 'oZy0Mw58RMSmyys0WR9xRp-y5v0U';
$message = new Text( [ 'content' => 'Hello world!' ] );
$server = $app->server;
print_r( $server );
/*
try
{
    $result = $staff->message( $message )->to( $openid )->send();
    print_r( $result );
}
catch(\Exception $ex)
{
    print_r( $ex );
}
*/

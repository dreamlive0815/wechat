<?php

$config = require( 'config.php' );

include $baseDir . '/vendor/autoload.php';

use EasyWeChat\Foundation\Application;
use EasyWeChat\Message\Text;

$app = new Application( $config );
$staff = $app->staff;
$openid = 'oQ4KVw14cKQ4lucVr4N8mJNY_Cro';
$message = new Text( [ 'content' => 'Hello world!' ] );
try
{
    $result = $staff->message( $message )->to( $openid )->send();
    var_dump( $result );
}
catch(\Exception $ex)
{
    print_r( $ex );
}

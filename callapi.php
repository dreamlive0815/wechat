<?php

$config_common = require( 'config_common.php' );
$config_wechat = require( 'config_wechat_beta.php' );

$wechatSDKDir = $config_common['wechatSDKDir'];
require( $wechatSDKDir . '/vendor/autoload.php' );

use EasyWeChat\Foundation\Application;
use EasyWeChat\Message\Text;

$app = new Application( $config_wechat );
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
    print_r( $ex->getMessage() );
}

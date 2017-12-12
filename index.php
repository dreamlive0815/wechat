<?php

$config_common = require( 'config_common.php' );
$config_wechat = require( 'config_wechat_beta.php' );

$wechatSDKDir = $config_common['wechatSDKDir'];
require( $wechatSDKDir . '/vendor/autoload.php' );

use EasyWeChat\Foundation\Application;

$app = new Application( $config_wechat );
$server = $app->server;
$response = $server->serve();
$response->send();


<?php

$baseDir = __DIR__;

$config_common = require( $baseDir . '/config_common.php' );
$config_wechat = require( $baseDir . '/config_wechat_beta.php' );

$wechatSDKDir = $config_common['wechatSDKDir'];
require( $wechatSDKDir . '/vendor/autoload.php' );

use EasyWeChat\Foundation\Application;
$app = new Application( $config_wechat );
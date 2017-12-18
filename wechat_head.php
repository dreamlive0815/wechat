<?php

$baseDir = __DIR__;

require_once( $baseDir . '/autoload.php' );

use Config\Config;

$confCommon = Config::get( 'Common' );
$confWechat = Config::get( 'Wechat' );

$wechatSDKDir = $confCommon->wechatSDKDir;
require( $wechatSDKDir . '/vendor/autoload.php' );

use EasyWeChat\Foundation\Application;
$app = new Application( $confWechat->toArray() );
$GLOBALS['app'] = $app;
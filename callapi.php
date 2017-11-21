<?php

$config = require( 'config.php' );

include $baseDir . '/vendor/autoload.php';
use EasyWeChat\Foundation\Application;

$app = new Application( $config );
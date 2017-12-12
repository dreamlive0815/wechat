<?php

$useDB = true;

require( './head.php' );
require( './wechat_head.php' );

use Util\Session\SessionUtil as SU;

//SU::start();
session_start();

$oauth = $app->oauth;
$user = $oauth->user();

$_SESSION['wechat_user'] = $user->toArray();
$target_url = $_SESSION['target_url'];
if( !$target_url ) $target_url = 'View/index.php';

header( 'Location: '. $target_url );
die( 0 );
<?php

$useDB = true;

require( './head.php' );
require( './wechat_head.php' );

use Util\Session\SessionUtil as SU;
use Control\UserController as US;

US::startSession();

$oauth = $app->oauth;
$user = $oauth->user();

$_SESSION['openid'] = $user['id'];
$_SESSION['encoded_info'] = base64_encode( json_encode( $user ) );

$target_url = SU::getval( 'target_url' );
if( !$target_url ) $target_url = 'View/account.php';

header( 'Location: '. $target_url );
die( 0 );
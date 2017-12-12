<?php

require( './head.php' );

require( './wechat_head.php' );

use Util\Session\SessionUtil as SU;

SU::start();

$oauth = $app->oauth;
$user = $oauth->user();

$_SESSION['wechat_user'] = $user->toArray();
$target_url = SU::getVal( 'target_url' );
if( $target_url )
{
    //header( 'Location: '. $target_url );
}

print_r( $_SESSION['wechat_user'] );
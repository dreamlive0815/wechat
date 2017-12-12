<?php

$useDB = true;

require( '../head.php' );

use Util\ViewUtil as VU;
use Util\Session\SessionUtil as SU;

//SU::start();
session_start();

$wechat_user = $_SESSION['wechat_user'];
if( !$wechat_user )
{
    require( '../wechat_head.php' );
    $oauth = $app->oauth;
    
    $_SESSION['target_url'] = 'View/account.php';
    $oauth->redirect()->send();
    die( 0 );
}

print_r( $wechat_user );


VU::head( 'test' );
VU::foot();
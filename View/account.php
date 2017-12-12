<?php

$useDB = true;

require( '../head.php' );

use Util\ViewUtil as VU;
use Util\Session\SessionUtil as SU;

SU::start();

$openid = SU::getVal( 'wechat_openid' );
if( !$openid )
{
    require( '../wechat_head.php' );
    $oauth = $app->oauth;
    
    $_SESSION['target_url'] = 'View/account.php';
    $oauth->redirect()->send();
    die( 0 );
}


VU::head( 'test' );
VU::foot();

echo 'success';
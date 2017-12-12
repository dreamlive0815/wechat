<?php

$useDB = true;

require( '../head.php' );

use Util\ViewUtil as VU;
use Util\Session\SessionUtil as SU;

//SU::start();
session_start();

$wechat_user = $_SESSION['wechat_user'];

print_r( $wechat_user );


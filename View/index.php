<?php

$useDB = true;

require( '../head.php' );

use Util\Session\SessionUtil as SU;
use Control\UserController as US;

US::startSession();

$encodedInfo = SU::getVal( 'encoded_info' );

$a = base64_decode( $encodedInfo );
$a = json_decode( $a, true );
print_r( $a );



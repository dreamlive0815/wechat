<?php

$useDB = true;

require( '../head.php' );

use Util\Session\SessionUtil as SU;

//SU::start();
session_start();

print_r( $_SESSION );


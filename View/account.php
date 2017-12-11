<?php

require( 'head.php' );

use Util\ViewUtil as VU;
use Util\Session\SessionUtil as SU;

SU::start();

VU::head( 'test' );
VU::foot();
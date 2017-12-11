<?php

require( '../autoload.php' );

use Util\ErrorUtil as EU;
use Util\FilterUtil as FU;
use Util\Log\LogUtil as LU;

try
{
    $logger = LU::create( 'FS', __DIR__ . '/../debug', 'error.log' );
    LU::addInstance( 'errorLogger', $logger );
    LU::addInstance( 'exceptionLogger', $logger );
}
catch( Exception $ex ) {}

EU::setGlobalErrorHandler();
EU::setGlobalExceptionHandler();

FU::addFilter( 'exceptionHandler', function( $info ) {
    $ex = $info['exception'];
    echo $ex->getMessage();
    return $info;
}, 999 );
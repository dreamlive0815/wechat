<?php

require( '../autoload.php' );
require( 'Cloud.php' );

use Util\CommonUtil as CU;
use Util\ErrorUtil as EU;
use Util\FilterUtil as FU;
use Util\Log\LogUtil as LU;

try
{
    $logger = LU::create( 'FS', __DIR__ . '/../debug', 'error.log' );
    LU::addInstance( 'errorLogger', $logger );
    LU::addInstance( 'exceptionLogger', $logger );
}
catch(Exception $ex)
{
    echo $ex->getMessage();
}

EU::setGlobalErrorHandler();
EU::setGlobalExceptionHandler();

FU::addFilter( 'exceptionHandler', function( $info ) {
    echo $info['exception']->getMessage();
    return $info;
}, 999 );

$id = CU::getR( 'id' );

if( !preg_match( '/^\d+$/', $id ) )
{
    $id = Cloud::getSongID( $id );
}




<?php

$baseDir = __DIR__;

require( $baseDir . '/autoload.php' );

use Util\ErrorUtil as EU;
use Util\FilterUtil as FU;
use Util\Log\LogUtil as LU;
use Util\MySQLi\MySQLiUtilPool as DB;

try
{
    $logger = LU::create( 'FS', $baseDir . '/debug', 'error.log' );
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

if( isset( $useDB ) && $useDB )
{
    $config = require( $baseDir . '/config_db.php' );
    $instance = DB::getInstance( $config['host'], $config['username'], $config['password'], $config['table'] );
    DB::setDefault( $instance );
}
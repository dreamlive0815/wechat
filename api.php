<?php

require( 'autoload.php' );

use Util\CommonUtil as CU;
use Util\ErrorUtil as EU;
use Util\FilterUtil as FU;
use Util\Log\LogUtil as LU;

try
{
    $logger = LU::create( 'FS', __DIR__ . '/debug', 'error.log' );
    LU::addInstance( 'errorLogger', $logger );
    LU::addInstance( 'exceptionLogger', $logger );
}
catch( Exception $ex ) {}

EU::setGlobalErrorHandler();
EU::setGlobalExceptionHandler();

FU::addFilter( 'exceptionHandler', function( $info ) {
    $ex = $info['exception'];
    $code = $ex->getCode(); $msg = $ex->getMessage();
    if( !$code ) $code = 10001;
    \Control\Controller::output( $code, $msg );   
    return $info;
}, 999 );

$controller = CU::getR( 'controller' );
$action = CU::getR( 'action' );

$classname = "\\Control\\{$controller}Controller";
$controllerInstance = new $classname();
$methodname = "{$action}Action";

if( !method_exists( $controllerInstance, $methodname ) ) throw new Exception( '动作' . $action . '不存在' );

$controllerInstance->$methodname();


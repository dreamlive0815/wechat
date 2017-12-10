<?php

require( 'autoload.php' );

use Util\CommonUtil as CU;
use Util\ErrorUtil as EU;
use Util\FilterUtil as FU;

EU::setGlobalExceptionHandler();

FU::addFilter( 'exceptionHandler', function( $info ) {
    echo $info['exception']->getMessage();
    return $info;
}, 999 );

$controller = CU::getR( 'controller' );
$action = CU::getR( 'action' );

$classname = "\\Control\\{$controller}Controller";
$controllerInstance = new $classname();
$methodname = "{$action}Action";

if( !method_exists( $controllerInstance, $methodname ) ) throw new Exception( '动作' . $action . '不存在' );

$controllerInstance->$methodname();


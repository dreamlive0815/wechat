<?php

class AutoLoadException extends Exception
{
    function __construct( $errorMsg, $errorCode = 10001 )
    {
        parent::__construct( $errorMsg, $errorCode );
    }
}

spl_autoload_register( function( $class ) {
    $currentDir = realpath( __DIR__ );
    $filename = $currentDir . '/' . $class . '.php';
    echo $filename . "\n";
    if( !is_file( $filename ) || !is_readable( $filename ) )
    {
        throw new AutoLoadException( '无法加载类:' . $class );
    }
    require( $filename );
} );
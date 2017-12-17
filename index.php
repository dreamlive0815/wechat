<?php

$useDB = true;

require( './head.php' );
require( './wechat_head.php' );

use Util\FilterUtil;
use Util\Log\LogUtil;

$server = $app->server;
$server->setMessageHandler( function( $message ) {
    $messageType = ucfirst( $message->MsgType );
    if( !file_exists( "./Handler/{$messageType}.php" ) ) $messageType = 'Base';
    $handlerClass = "\\Handler\\{$messageType}";
    $reply = '';
    try
    {
        $handlerClass::$message = $message;
        $reply = $handlerClass::handle();
    }
    catch( \Exception $ex )
    {
        $reply = $ex->getMessage() . sprintf( '[%s]', $ex->getCode() );
        $exceptionLogger = LogUtil::getInstance( 'exceptionLogger' );
        if( $exceptionLogger ) $exceptionLogger->log( $ex->__toString() );
    }
    return $reply;
} );
$response = $server->serve();
$response->send();


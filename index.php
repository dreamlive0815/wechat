<?php

$useDB = true;

require( './head.php' );
require( './wechat_head.php' );

use Util\FilterUtil;
use Util\Log\LogUtil;
use Handler\Base;

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
        $query = Base::getQuery( 'Query' );
        $text = $ex->getMessage() . sprintf( '[%s]', $ex->getCode() );
        $reply = $query->onServerError( $ex->getCode(), $text );
        $exceptionLogger = LogUtil::getInstance( 'exceptionLogger' );
        if( $exceptionLogger ) $exceptionLogger->log( $ex->__toString() );
    }
    return $reply;
} );
$response = $server->serve();
$response->send();


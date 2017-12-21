<?php

namespace Handler;

use Util\FormatUtil as FU;
use Model\User;

class Base
{
    static $message;
    static $cmdArgs = [];
    static $usePlugin = true;

    static function getCmdArg( $key )
    {
        if( !isset( self::$cmdArgs[$key] ) ) return null;
        return self::$cmdArgs[$key];
    }

    static function getOpenid()
    {
        if( !self::$message ) return null;
        return self::$message->FromUserName;
    }

    static function handle()
    {

    }

    static function getQuery( $type )
    {
        $type = ucfirst( $type );
        $openid = self::getOpenid();
        $user = User::getUser( $openid );
        $user->update_time = FU::getMySQLDatetime();

        $classname = "\\Handler\\Query\\{$type}";
        $instance = new $classname( $user );
        if( self::getCmdArg( 'nocache' ) ) $instance->useCache = false;
        return $instance;
    }
}
<?php

namespace Handler;

use Util\FormatUtil as FU;
use Model\User;

class Base
{
    static $message;

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
        //if( !$user->id ) return static::redirectToSettingPage();
        $user->update_time = FU::getMySQLDatetime();

        $classname = "\\Handler\\Query\\{$type}";
        return new $classname( $user );
    }

    

    
}
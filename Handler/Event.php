<?php

namespace Handler;

use Model\User;

class Event extends Base
{
    static function handle()
    {
        $event = self::$message->Event;

        switch( $event )
        {
            case 'subscribe':
                self::subscribe( 1 );
                return '欢迎关注';

            case 'unsubscribe':
                self::subscribe( 0 );
                return '';

            case 'CLICK':
                $eventKey = self::$message->EventKey;
                $eventKey = str_replace( '_', ' ', $eventKey );
                return Text::handleText( $eventKey );

        }

    }

    static function subscribe( $flag )
    {
        $flag = intval( $flag );
        $openid = self::getOpenid();
        $user = User::getUser( $openid );
        if( $user->id ) $user->follow = $flag;
    }
}
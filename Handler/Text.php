<?php

namespace Handler;

class Text extends Base
{
    static function handle()
    {
        $text = self::$message->Content;

        return self::handleText( $text );
    }

    static function handleText( $text )
    {
        $text = ucfirst( $text );
        switch( $text )
        {
            case 'Course':
                $query = self::getQuery( 'Course' );
                return $query->run();
   
        }
    }
}
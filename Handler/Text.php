<?php

namespace Handler;

class Text extends Base
{
    static function handle()
    {
        $query = self::getQuery( 'course' );
        return $query->run();
    }

    static function handleText( $text )
    {

    }
}
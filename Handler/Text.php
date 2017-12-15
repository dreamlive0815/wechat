<?php

namespace Handler;

class Text extends Base
{
    static function handle()
    {
        $query = self::getQuery( 'course' );
        //$query->useCache = false;
        return $query->run();
    }

    static function handleText( $text )
    {

    }
}
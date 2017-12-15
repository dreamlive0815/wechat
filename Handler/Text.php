<?php

namespace Handler;

class Text extends Base
{
    static function handle()
    {
        $query = self::getQuery( 'course' );
        $query->single(  );
        print_r( $query );
        return $query->run();
    }

    static function handleText( $text )
    {

    }
}
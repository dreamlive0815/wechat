<?php

namespace Handler;

class Text extends Base
{
    static function handle()
    {
        return self::Query( 'course' );
        $news = static::redirectToSettingPage();
        return $news;
    }

    static function handleText( $text )
    {

    }
}
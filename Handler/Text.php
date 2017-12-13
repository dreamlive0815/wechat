<?php

namespace Handler;

class Text extends Base
{
    static function handle( $message )
    {
        $news = self::redirectToSettingPage();
        return $news;
    }
}
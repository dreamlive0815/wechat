<?php

namespace Handler;

use Util\EnvironmentUtil as EU;
use EasyWeChat\Message\News;

class Base
{
    static function handle( $message )
    {

    }

    static function Query( $type )
    {

    }

    static function redirectToSettingPage( $filter = null, $title = '账号信息有误', $description = '点击这里设置账号信息' )
    {
        $protocol = EU::getRequestProtocol();
        $host = EU::getServerHost();
        $url = "{$protocol}://{$host}/wechat/View/account.php";
        if( $filter ) $url .= "?filter={$filter}";
        return self::getNew( [ 
            'title' => $title,
            'description' => $description,
            'url' => $url,
        ] );
    }

    static function getNew( array $args = [] )
    {
        $realArgs = [ 'title' => '', 'description' => '', 'url' => '', 'image' => '' ];
        $realArgs = array_merge( $realArgs, $args );
        return new News( $realArgs );
    }
}
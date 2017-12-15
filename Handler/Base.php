<?php

namespace Handler;

use Util\EnvironmentUtil as EU;
use Util\FormatUtil as FU;
use Model\User;
use EasyWeChat\Message\News;

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

    static function Query( $type )
    {
        $type = ucfirst( $type );
        $openid = self::getOpenid();
        $user = User::getUser( $openid );
        if( !$user->id ) return static::redirectToSettingPage();
        $user->update_time = FU::getMySQLDatetime();

        $classname = "\\Handler\\Query\\{$type}";
        return $classname::run( $user );
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
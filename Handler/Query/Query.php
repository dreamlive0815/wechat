<?php

namespace Handler\Query;

use Util\CurlUtil as HTTP;
use Util\MySQLi\MySQLiUtilPool as DB;
use Model\Cache;

class Query
{
    static $apiURL = 'https://m.zstu.edu.cn/capturer/index.php';

    static function buildArgs( $user )
    {
        return [ 'uid' => $user->sid ];
    }

    static function run( $user )
    {
        $args = static::buildArgs( $user );
        $type = static::getType();
        $cache = Cache::get( array_merge( $args, [ 'type' => $type, 'openid' => $user->openid ] ) );
        print_r( $user );
        print_r( $cache );
    }

    static function getType()
    {
        $class = static::class;
        $a = explode( "\\", $class );
        return end( $a );
    }

    static function onValidateError( $errorMsg )
    {

    }

    static function onServerError( $errorMsg )
    {

    }
}
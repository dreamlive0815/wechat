<?php

namespace Handler\Query;

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
        print_r( $user );
        print_r( $args );
    }

    static function onValidateError( $errorMsg )
    {

    }

    static function onServerError( $errorMsg )
    {

    }
}
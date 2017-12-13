<?php

namespace Util;

class EnvironmentUtil
{
    static function isHttpsRequest()
    {
        if( isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] == 'on' ) return true;
        if( isset( $_SERVER['SERVER_PORT'] ) && $_SERVER['SERVER_PORT'] == 443 ) return true;
        return false;
    }

    static function getRequestProtocol()
    {
        if( self::isHttpsRequest() ) return 'https';
        return 'http';
    }

    static function getServerHost()
    {
        return $_SERVER['HTTP_HOST'];
    }
}
<?php

namespace Util\MySQLi;

use Util\MySQLi\MySQLiUtil;

class MySQLiUtilPool
{
    static $utils = [];

    static $default;

    static function addInstance( $key, MySQLiUtil $util )
    {
        if( isset( self::$utils[$key] ) ) throw new \Exception( '键名已存在' );
        self::$utils[$key] = $util;
    }

    static function getInstanceByKey( $key )
    {
        if( !isset( self::$utils[$key] ) ) return null;
        return self::$utils[$key];
    }

    static function setDefault( MySQLiUtil $util )
    {
        self::$default = $util;
    }

    static function __callStatic( $func, $args )
    {
        if( !self::$default ) return null;
        if( !method_exists( self::$default, $func ) ) return null;
        return call_user_func_array( [ self::$default, $func ], $args );
    }

    static function getInstance( $host, $username, $password, $dbname = null )
    {
        foreach( self::$utils as $util )
        {
            if( $host == $util->host && $username == $util->username && $password == $util->password && $dbname == $util->dbname ) return $util;
        }
        $util = new MySQLiUtil( $host, $username, $password, $dbname );
        self::$utils[] = $util;
        return $util;
    }
}
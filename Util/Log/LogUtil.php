<?php

namespace Util\Log;

use Util\Log\FileSystemLogUtil;
use Util\Exceptions\LogUtilException;

abstract class LogUtil
{
    static $instances = [];
    static $default;
    static $debug = false;

    static function getInstance( $key )
    {
        $key = strval( $key );
        if( isset( self::$instances[$key] ) ) return self::$instances[$key];
        return null;
    }

    static function addInstance( $key, LogUtil $instance )
    {
        $key = strval( $key );
        if( isset( self::$instances[$key] ) ) throw new LogUtilException( '键名已存在' );
        self::$instances[$key] = $instance;
    }

    static function hasInstance( $key )
    {
        $key = strval( $key );
        return isset( self::$instances[$key] );
    }

    static function setDefault( LogUtil $instance )
    {
        self::$default = $instance;
    }

    static function __callStatic( $func, $args )
    {
        if( !self::$default ) return null;
        $func = str_replace( '_', '', $func );
        if( !method_exists( self::$default, $func ) ) return null;
        return call_user_func_array( [ self::$default, $func ], $args );
    }

    static function create()
    {
        $args = func_get_args();
        $argc = func_num_args();
        if( !$argc ) throw new LogUtilException( '至少传递一个参数(LogUtil类型)' );
        $type = $args[0]; array_shift( $args );
        $instance = null;
        switch( $type )
        {
            case 'FS' :
                $instance = new FileSystemLogUtil( ...$args );
                break;
        }
        self::$default = $instance;
        if( !$instance ) throw new LogUtilException( '无法创建LogUtil:' . $type );
        return $instance;
    }

    abstract function log( $content );

    abstract function debug( $content );
}
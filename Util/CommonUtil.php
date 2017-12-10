<?php

namespace Util;

class CommonUtil
{
    static $map = [];

    static function getVal( $key )
    {
        $key = strval( $key );
        return isset( self::$map[$key] ) ? self::$map[$key] : null;
    }

    static function setVal( $key, $val = null )
    {
        if( is_array( $key ) )
        {
            foreach( $key as $k => $v )
            {
                $k = strval( $k );
                self::$map[$k] = $v;
            }
        }
        else
        {
            $key = strval( $key );
            self::$map[$key] = $val;
        }
    }

    static function getR( $key )
    {
        return isset( $_REQUEST[$key] ) ? $_REQUEST[$key] : null;
    }

    static function getURLInfo( $url )
    {
        $path = $url; $args = [];
        $p = strpos( $url, '?' );
        
        if( $p !== false )
        {
            $path = substr( $url, 0, $p );
            $argStr = substr( $url, $p + 1, strlen( $url ) - $p - 1 );
            $a = explode( '&', $argStr );
            foreach( $a as $i )
            {
                $aa = explode( '=', $i );
                $k = $aa[0];
                $v = isset( $aa[1] ) ? $aa[1] : null;
                $args[$k] = $v;
            }
        }

        return [
            'path' => $path,
            'args' => $args,
        ];
    }

    static function getURLArgs( $url )
    {
        $info = self::getURLInfo( $url );
        return $info['args'];
    }

    static function getURL( $oldURL, array $override = [], array $drop = [] )
    {
        $info = self::getURLInfo( $oldURL );
        $args = $info['args'];
        $args = array_merge( $args, $override );
        $args = array_diff_key( $args, $drop );
        $s = ''; $f = 1;
        foreach( $args as $k => $v )
        {
            if( $f ) $f = 0; else $s .= '&';
            $s .= $k . '=' . $v;
        }
        if( $s ) $s = '?' . $s;
        return $info['path'] . $s;
    }
}
<?php

namespace Util;

class CommonUtil
{
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
        print_r( $info );
        return $info['args'];
    }
}
<?php

namespace Util;

class JsonUtil
{
    static function parse( $s )
    {
        return json_decode( $s, true );
    }

    static function stringify( $json, $format = false )
    {
        $flags = JSON_UNESCAPED_UNICODE;
        if( $format ) $flags = $flags | JSON_PRETTY_PRINT;
        return json_encode( $json, $flags );
    }
}
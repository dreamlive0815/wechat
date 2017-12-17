<?php

namespace Util;

class HeaderUtil
{
    static $contentTypes = [
        'bin' => 'application/octet-stream',
        'json' => 'application/json',
        'mp3' => 'audio/mp3',
        'pdf' => 'application/pdf',
    ];

    static function code( $code )
    {
        $code = intval( $code );
        http_response_code( $code );
    }

    static function type( $type, $charset = null )
    {
        if( isset( self::$contentTypes[$type] ) ) $type = self::$contentTypes[$type];
        $content = "Content-type: {$type}";
        if( $charset ) $content .= "; charset={$charset}";
        header( $content );
    }

    static function length( $length )
    {
        $content = "Content-Length: {$length}";
        header( $content );
    }

    static function filename( $filename, $attachment = true )
    {
        $s = ''; if( $attachment ) $s = 'attachment; ';
        $content = "Content-Disposition: {$s}filename={$filename}";
        header( $content );
    }
}
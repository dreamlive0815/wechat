<?php

namespace Util;

class HeaderUtil
{
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
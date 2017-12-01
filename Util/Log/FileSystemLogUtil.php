<?php

namespace Util\Log;

use Util\Log\LogUtil;
use Util\Exceptions\LogUtilException;

class FileSystemLogUtil extends LogUtil
{
    public $dir;
    public $filename;

    function __construct( $dir, $filename )
    {
        $dir = strval( $dir ); $filename = strval( $filename );
        $dir = realpath( $dir );
        
        if( !is_dir( $dir ) || !is_writeable( $dir ) ) throw new LogUtilException( '用于Log的文件夹不可写' );
        if( preg_match( '/["<>:\+\/\*\?\|\\\]/', $filename ) ) throw new LogUtilException( '用于Log的文件名不合法' );
        $this->dir = $dir; $this->filename = $filename;
    }

    function log( $content )
    {
        $content = date( '[Y-m-d H:i:s] ' ) . $content;
        $content .= "\n\n";
        $filepath = $this->dir . '/' . $this->filename;
        error_log( $content, 3, $filepath );
    }

    function debug( $content )
    {
        if( !static::$debug ) return;
        $this->log( $content );
    }
}
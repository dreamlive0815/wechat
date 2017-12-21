<?php

namespace Util\FileSystem;

use Util\Exceptions\FileSystemUtilException as EX;

class Base
{
    protected $items = [];

    function __construct( $path )
    {
        $this->path = realpath( $path );
        $this->exist = $this->exist();
        if( !$this->exist ) throw $this->getException( '文件或者目录不存在' );
    }

    function exist()
    {
        return file_exists( $this->path );
    }

    function getException( $errorMsg, $errorCode = 10002 )
    {
        return new EX( $errorMsg, $errorCode );
    }

    function __get( $key )
    {
        return isset( $this->items[$key] ) ? $this->items[$key] : null;
    }

    function __set( $key, $val )
    {
        $this->items[$key] = $val;
    }
}
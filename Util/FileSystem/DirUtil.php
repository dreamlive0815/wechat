<?php

namespace Util\FileSystem;

class DirUtil extends Base
{
    function exist()
    {
        return is_dir( $this->path );
    }

    function getChildren()
    {
        $dirHandle = opendir( $this->path );
        if( !$dirHandle ) throw $this->getException( '无法读取文件夹' );
        $children = [];
        while( ( $name = readdir( $dirHandle ) ) !== false )
        {
            if( $name == '.' || $name == '..' ) continue;
            $fullPath = $this->path . '/' . $name;
            $childrenInfo = [ 'name' => $name, 'fullpath' => $fullPath ];
            if( is_dir( $fullPath ) ) $children['dir'][] = $childrenInfo; else $children['file'][] = $childrenInfo;
        }
        closedir( $dirHandle );
        return $children;
    }

    function getFileChildren()
    {
        $children = $this->getChildren();
        return $children['file'];
    }
}
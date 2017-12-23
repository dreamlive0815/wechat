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

    function createFile( $name, $content, $overwrite = true )
    {
        if( !is_writable( $this->path ) ) throw new \Exception( '文件夹不可写,无法创建文件' );
        if( !$this->isNameLegal( $name ) ) throw new \Exception( '文件名不合法,无法创建文件' );
        $fullPath = $this->path . '/' . $name;
        if( !$overwrite && is_file( $fullPath ) ) throw new \Exception( '同名文件已存在,无法创建文件' );
        return file_put_contents( $fullPath, $content );
    }
}
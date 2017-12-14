<?php

namespace Model;

use Util\MySQLi\MySQLiUtilPool as DB;

class Model
{
    public $map = [];

    function __construct( array $map = [] )
    {
        $this->map = $map;
    }

    public function __get( $key )
    {
        return isset( $this->map[$key] ) ? $this->map[$key] : null;
    }

    public function __set( $key, $val )
    {
        $this->map[$key] = $val;
    }

    static function checkDB()
    {
        if( !DB::$default ) throw new Exception( '请先初始化数据库工具' );
    }
}
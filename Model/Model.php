<?php

namespace Model;

use Util\MySQLi\MySQLiUtilPool as DB;

class Model
{
    static $table;

    public $map = [];

    function __construct( array $map = [] )
    {
        $this->map = $map;
    }

    static function checkDB()
    {
        if( !DB::$default ) throw new Exception( '请先初始化数据库工具' );
    }

    public function __get( $key )
    {
        return isset( $this->map[$key] ) ? $this->map[$key] : null;
    }

    public function __set( $key, $val )
    {
        $this->map[$key] = $val;
    }

    public function toArray()
    {
        return $this->map;
    }

    static function __callStatic( $func, $args )
    {
        $class = static::getClassName();
        if( $func == "get{$class}" ) return static::getInstance( $args[0] );
    }

    //args必须可以唯一确定一个模型
    static function getInstance( array $args )
    {
        self::checkDB();
        $q = DB::$default->getQuery( static::getTableName() );
        foreach( $args as $k => $v )
        {
            $q->where( $k, $v );
        }
        $a = $q->limit( 1 )->get()->fir();
        if( !$a ) $a = [];
        return new static( $a );
    }

    //args必须可以唯一确定一个模型
    static function updateInstance( array $args, array $set, array $createSet = [] )
    {
        $model = static::getInstance( $args );

        if( $model->id )
        {
            echo $q->field( array_keys( $args ) )->_insert_( $args );
            
        }
    }

    static function getTableName()
    {
        if( static::$table ) return static::$table;
        return static::getClassName();
    }

    static function getClassName()
    {
        $class = static::class;
        $a = explode( "\\", $class );
        return end( $a );
    }
}
<?php

namespace Model;

use Util\CommonUtil as CU;
use Util\MySQLi\MySQLiUtilPool as DB;

class User extends Model
{
    function __construct( array $map = [] )
    {
        $this->map = $map;
    }

    private static function checkDB()
    {
        if( !DB::$default ) throw new Exception( '请先初始化数据库工具' );
    }

    static function getUserByOpenid( $openid, $arrayType = false )
    {
        self::checkDB(); 
        $q = DB::$default->getQuery( 'account' );
        $a = $q->where( 'openid', $openid )->limit( 1 )->get()->fir();
        if( !$a ) $a = [];
        if( $arrayType ) return $a;
        $instance = new User( $a );
        return $instance;
    }
}
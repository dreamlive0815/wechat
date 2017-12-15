<?php

namespace Model;

use Util\FormatUtil as FU;
use Util\MySQLi\MySQLiUtilPool as DB;

class User extends Model
{
    static $table = 'account';

    static function getUserByOpenid( $openid, $arrayType = false )
    {
        self::checkDB(); 
        $q = DB::$default->getQuery( static::getTableName() );
        $a = $q->where( 'openid', $openid )->limit( 1 )->get()->fir();
        if( !$a ) $a = [];
        if( $arrayType ) return $a;
        $instance = new self( $a );
        return $instance;
    }

    static function createOrUpdateUser( $openid, array $map )
    {
        self::checkDB();
        $q = DB::$default->getQuery( static::getTableName() );
        $a = $q->where( 'openid', $openid )->limit( 1 )->get()->fir();
        if( !$a )
        {
            $q->field( [ 'openid' , 'register_time' ] )->insert( [ $openid, FU::getMySQLDatetime() ] );
        }

        $set = [];
        foreach( $map as $k => $v )
        {
            if( $v !== null ) $set[$k] = $v;
        }
        $set['update_time'] = FU::getMySQLDatetime();
        return $q->set( $set )->update();
    }
}
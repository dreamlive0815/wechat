<?php

namespace Model;

use Util\FormatUtil as FU;
use Util\MySQLi\MySQLiUtilPool as DB;

class User extends Model
{
    static $table = 'account';

    static function wrapArg( $arg )
    {
        if( is_array( $arg ) ) return [ 'openid' => $arg['openid'] ];  
        return [ 'openid' => strval( $arg ) ];
    }

    static function getUser( $arg )
    {
        $args = self::wrapArg( $arg );
        return self::getInstance( $args );
    }

    static function updateUser( $arg, array $set )
    {
        $args = self::wrapArg( $arg );
        $set['update_time'] = FU::getMySQLDatetime();
        $createSet = [ 'register_time' => FU::getMySQLDatetime() ];
        return self::updateInstance( $args, $set, $createSet );
    }
}
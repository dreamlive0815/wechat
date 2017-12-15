<?php

namespace Model;

use Util\FormatUtil as FU;
use Util\MySQLi\MySQLiUtilPool as DB;

class User extends Model
{
    static $table = 'account';

    static function getUser( $arg )
    {
        $args = is_array( $arg ) ? $arg : [ 'openid' => $arg ];
        return self::getInstance( $args );
    }

    static function updateUser( $arg, array $set )
    {
        $args = is_array( $arg ) ? $arg : [ 'openid' => $arg ];
        $set['update_time'] = FU::getMySQLDatetime();
        $createSet = [ 'register_time' => FU::getMySQLDatetime() ];
        return self::updateInstance( $args, $set, $createSet );
    }
}
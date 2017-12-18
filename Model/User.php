<?php

namespace Model;

use Util\FormatUtil as FU;
use Util\MySQLi\MySQLiUtilPool as DB;

class User extends Model
{
    static $table = 'account';

    static function wrapArgs( $args )
    {
        if( !is_array( $args ) ) $args = [ 'openid' => strval( $args ) ];
        $args = array_merge( [ 'openid' => '' ], $args );
        $realArgs = [
            'openid' => $args['openid'],
        ];
        return $realArgs;
    }

    static function getUser( $args )
    {
        return self::getInstance( self::wrapArgs( $args ) );
    }

    static function updateUser( $args, array $set )
    {
        $set['update_time'] = FU::getMySQLDatetime();
        $createSet = [ 'register_time' => FU::getMySQLDatetime() ];
        return self::updateInstance( self::wrapArgs( $args ), $set, $createSet );
    }

    function update( array $set )
    {
        $set['update_time'] = FU::getMySQLDatetime();
        return parent::update( $set );
    }
}
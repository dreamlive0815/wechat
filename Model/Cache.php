<?php

namespace Model;

use Util\FormatUtil as FU;
use Util\MySQLi\MySQLiUtilPool as DB;

class Cache extends Model
{
    static function wrapArgs( array $args )
    {
        $args = array_merge( [ 'uid' => '', 'passwd' => '', 'type' => '', 'owner' => '' ], $args );
        $realArgs = [
            'uid' => $args['uid'], 'passwd' => $args['passwd'],
            'type' => $args['type'], 'owner' => $args['owner'],
        ];
        return $realArgs;
    }

    static function getCache( array $args )
    {
        return self::getInstance( self::wrapArgs( $args ) );
    }

    static function updateCache( array $args, array $set )
    {
        $set['update_time'] = FU::getMySQLDatetime();
        return self::updateInstance( self::wrapArgs( $args ), $set );
    }
}
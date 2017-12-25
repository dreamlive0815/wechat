<?php

namespace Model;

use Util\FormatUtil as FU;

class Log extends Model
{
    static function wrapArgs( array $args )
    {
        $args = array_merge( [ 'openid' => '', 'update_time' => '' ], $args );
        $realArgs = [
            'openid' => $args['openid'], 'update_time' => $args['update_time'],
        ];
        return $realArgs;
    }

    static function getLog( array $args )
    {
        return self::getInstance( self::wrapArgs( $args ) );
    }

    static function updateLog( array $args, array $set )
    {
        $args['update_time'] = $set['update_time'] = FU::getMySQLDatetime();
        return self::updateInstance( self::wrapArgs( $args ), $set );
    }
}
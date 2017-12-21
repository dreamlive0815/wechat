<?php

namespace Model;

class Setting extends Model
{
    static function wrapArgs( array $args )
    {
        $args = array_merge( [ 'openid' => '', 'type' => '' ], $args );
        $realArgs = [
            'openid' => $args['openid'], 'type' => $args['type'],
        ];
        return $realArgs;
    }

    static function getSetting( array $args )
    {
        return self::getInstance( self::wrapArgs( $args ) );
    }

    static function updateSetting( array $args, array $set )
    {
        return self::updateInstance( self::wrapArgs( $args ), $set );
    }

}
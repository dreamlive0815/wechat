<?php

namespace Model;

class Reply extends Model
{
    static function wrapArgs( $args )
    {
        if( !is_array( $args ) ) $args = [ 'key' => strval( $args ) ];
        $args = array_merge( [ 'key' => '' ], $args );
        $realArgs = [
            'key' => $args['key'],
        ];
        return $realArgs;
    }

    static function getReply( $args )
    {
        return self::getInstance( self::wrapArgs( $args ) );
    }

    static function updateReply( $args )
    {
        return self::updateInstance( self::wrapArgs( $args ), $set );
    }
}
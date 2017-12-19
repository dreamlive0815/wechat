<?php

namespace Model;

class Reply extends Model
{
    static function wrapArgs( $args )
    {
        if( !is_array( $args ) ) $args = [ 'keyword' => strval( $args ) ];
        $args = array_merge( [ 'keyword' => '' ], $args );
        $realArgs = [
            'keyword' => $args['keyword'],
        ];
        return $realArgs;
    }

    static function getReply( $args )
    {
        return self::getInstance( self::wrapArgs( $args ) );
    }

    static function updateReply( $args, array $set )
    {
        return self::updateInstance( self::wrapArgs( $args ), $set );
    }
}
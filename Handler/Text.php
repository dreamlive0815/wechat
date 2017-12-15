<?php

namespace Handler;

class Text extends Base
{
    static function handle()
    {
        $text = self::$message->Content;

        return self::handleText( $text );
    }

    static function handleText( $text )
    {
        self::$cmdArgs = self::parseCmdArgs( $text );
        $base = strtolower( self::getCmdArg( 'base' ) );
        $base = ucfirst( $base );
        switch( $base )
        {
            case 'Course':
                $query = self::getQuery( 'Course' );
                if( self::getCmdArg( 'today' ) ) $query->today = true;
                if( self::getCmdArg( 'nocache' ) ) $query->useCache = false;
                $year = self::getCmdArg( 'year' );
                $semester = self::getCmdArg( 'semester' );
                if( preg_match( '/\d{4}-\d{4}/', $year ) && preg_match( '/\d/', $semester ) )
                {
                    $query->year = $year;
                    $query->semester = $semester;
                }
                return $query->run();
                
        }
    }

    static function parseCmdArgs( $text )
    {
        $args = [];
        $text = trim( $text );
        $text = substr( $text, 0, 64 );
        $pairs = explode( ' ', $text );
        $base = reset( $pairs );
        array_shift( $pairs );
        foreach( $pairs as $pair )
        {
            if( !$pair ) continue;
            $kv = explode( '=', $pair );
            $k = $kv[0]; $k = strtolower( $k );
            $v = isset( $kv[1] ) ? $kv[1] : true;
            $args[$k] = $v;
        }
        $args['base'] = $base;
        return $args;
    }
}
<?php

namespace Handler;

use EasyWeChat\Message\News;
use Util\EnvironmentUtil as EU;

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

        $class = '\Tool\Cloud'; $tool = new $class();
        if( $tool->hasSongURL( $text ) )
        {
            $id = $tool->getSongID( $text );
            $info = $tool->getSongInfo( $id );
            $url = $info['url'];
            if( self::getCmdArg( 'download' ) )
            {
                return sprintf( '%s/wechat/View/download.Cloud.php?id=%s', EU::getServerBaseURL(), $id );
            }
            return new News( [
                'title' => $info['name'],
                'description' => '点击下载',
                'url' => $url,
            ] );
        }
        
        $base = strtolower( self::getCmdArg( 'base' ) );
        $base = ucfirst( $base );
        switch( $base )
        {
            case 'Course':
            case 'Coursebeta':
                if( $base ) $base = 'CourseBeta';
                $query = self::getQuery( $base );
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
        $text = substr( $text, 0, 400 );
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
<?php

namespace Handler;

use EasyWeChat\Message\News;
use Util\EnvironmentUtil as EU;
use Config\Config;

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
            $url = sprintf( '%s/%s/View/download.Cloud.php?id=%s', EU::getServerBaseURL(), Config::basename,$id );
            return new News( [
                'title' => $info['name'],
                'description' => '点击下载',
                'url' => $url,
            ] );
        }

        $class = '\Tool\CNKI'; $tool = new $class();
        if( $tool->hasArticleURL( $text ) )
        {
            $url = $tool->getArticleURL( $text );
            $info = $tool->getArticleInfo( $url );
            $downloadURL = $tool->getArticleDownloadURL( $info );
            return new News( [
                'title' => $info['filename'],
                'description' => '点击下载',
                'url' => $downloadURL,
            ] );
        }
        
        $base = strtolower( self::getCmdArg( 'base' ) );
        $base = ucfirst( $base );
        switch( $base )
        {
            case 'Course':
            case 'Coursebeta':
                if( $base == 'Coursebeta' ) $base = 'CourseBeta';
                $query = self::getQuery( $base );
                if( self::getCmdArg( 'today' ) ) $query->today = true;
                $year = self::getCmdArg( 'year' );
                $semester = self::getCmdArg( 'semester' );
                if( preg_match( '/\d{4}-\d{4}/', $year ) && preg_match( '/\d/', $semester ) )
                {
                    $query->year = $year;
                    $query->semester = $semester;
                }
                return $query->run();

            case 'Setting':
                $query = self::getQuery( 'Query' );
                return $query->getRedirectSettingNews();

            case 'Error':
                $query = self::getQuery( 'Query' );
                return $query->getErrorImageURL();
                
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
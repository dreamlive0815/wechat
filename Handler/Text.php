<?php

namespace Handler;

use EasyWeChat\Message\News;
use Util\EnvironmentUtil as EU;
use Util\JsonUtil as JSON;
use Util\MySQLi\MySQLiUtilPool as DB;
use Util\FileSystem\DirUtil as DIR;
use Config\Config;
use Model\Reply;
use Model\Log;


class Text extends Base
{
    static $callTimes = 0;

    static function handle()
    {
        $text = self::$message->Content;

        Log::updateLog( [ 'openid' => self::getOpenid() ], [ 'data' => $text ] );

        return self::handleText( $text );
    }

    static function handleText( $text )
    {
        self::$cmdArgs = self::parseCmdArgs( $text );

        if( self::$usePlugin )
        {
            $dir = new DIR( __DIR__ . '/Plugin'  );
            $files = $dir->getFileChildren();
            foreach( $files as $file )
            {
                $name = $file['name'];
                $name = preg_replace( '/\..+/', '', $name );
                if( $name == 'Plugin' ) continue;
                $class = "\\Handler\\Plugin\\{$name}"; $plugin = new $class();
                $plugin->openid = self::getOpenid();
                $return = $plugin->run( $text );
                if( $plugin->handled ) return $return;
            }
        }
        
        $base = strtolower( self::getCmdArg( 'base' ) );
        $base = ucfirst( $base );
        if( self::isQuery( $base ) ) return self::runQuery( $base );
        switch( $base )
        {
            case 'Ecardtoday':
                $cmd = sprintf( 'Ecard startdate=%s enddate=%s', date( 'Y-m-d' ), date( 'Y-m-d' ) );
                return self::handleText( $cmd );

            case 'Ecardthismonth':
                $cmd = sprintf( 'Ecard startdate=%s enddate=%s', date( 'Y-m-d', time() - 3600 * 24 * 30 ), date( 'Y-m-d' ) );
                return self::handleText( $cmd );

            case 'Getcache':
                $type = self::getCmdArg( 'type' );
                if( !$type && count( self::$cmdArgs ) > 1 )
                {
                    $keys = array_keys( self::$cmdArgs );
                    $type = reset( $keys );
                }
                $type = ucfirst( $type );
                if( !self::isQuery( $type ) ) return '';
                return sprintf( '%s/%s/View/cache.php?type=%s', EU::getServerBaseURL(), Config::basename, $type );

            case 'Clearcache':
                $q = DB::$default->getQuery( 'cache' );
                $q->where( 'owner', self::getOpenid() );
                if( $q->delete() ) return '缓存已清除';
                return '无法清除缓存';

            case 'Getopenid':
                return self::getOpenid();

            case 'Setting':
                $query = self::getQuery( 'Query' );
                return $query->getRedirectSettingNews( null, '账号信息设置页面' );

            case 'Error':
                $query = self::getQuery( 'Query' );
                return new News( [ 'image' => $query->getErrorImageURL() ] );
                
        }

        ++self::$callTimes;
        if( self::$callTimes > 3 ) throw new \Exception( '递归调用次数过多' );
        $reply = Reply::getReply( $base );
        if( $reply->id )
        {
            switch( $reply->type )
            {
                case 'text':
                    return $reply->data;

                case 'news':
                    $newsArray = unserialize( $reply->data );
                    return $newsArray;
                
                case 'cmd':
                    return self::handleText( $reply->data );
            }
        }

    }

    static function isQuery( $text )
    {
        $list = [
            'Course', 'Coursebeta',
            'Score', 'Scorebeta',
            'Ecard',
            'Exam',
            'Net',
            'Borrow',
        ];
        return in_array( $text, $list );
    }

    static function renderQueryName( $text )
    {
        if( $text == 'Coursebeta' ) $text = 'CourseBeta';
        if( $text == 'Scorebeta' ) $text = 'ScoreBeta';
        return $text;
    }

    static function runQuery( $text )
    {
        $text = self::renderQueryName( $text );
        $query = self::getQuery( $text );
        if( self::getCmdArg( 'today' ) ) $query->today = true;
        if( self::getCmdArg( 'currentsemester' ) ) $query->currentSemester = true;
        $year = self::getCmdArg( 'year' );
        $semester = self::getCmdArg( 'semester' );
        if( preg_match( '/\d{4}-\d{4}/', $year ) && preg_match( '/\d/', $semester ) )
        {
            $query->year = $year;
            $query->semester = $semester;
        }
        $startDate = self::getCmdArg( 'startdate' );
        $endDate = self::getCmdArg( 'enddate' );
        if( preg_match( '/\d{4}-\d{2}-\d{2}/', $startDate ) && preg_match( '/\d{4}-\d{2}-\d{2}/', $endDate ) )
        {
            $query->startDate = $startDate;
            $query->endDate = $endDate;
        }
        return $query->run();
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
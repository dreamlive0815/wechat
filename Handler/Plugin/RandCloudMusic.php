<?php

namespace Handler\Plugin;

use Tool\Cloud as CM;
use Model\Setting;
use Config\Config;
use Util\EnvironmentUtil as EU;

class RandCloudMusic extends Plugin
{
    function run( $text )
    {
        if( preg_match( '/^(音乐|music)(\s+link)?$/i', $text, $match ) )
        {
            $setting = Setting::getSetting( [ 'openid' => $this->openid, 'type' => 'cloud_user_id' ] );
            $u_id = $user_id = '352148975';
            if( $setting->id ) $user_id = $setting->data;
            try { $records = CM::getUserWeekPlayRecordInfo( $user_id ); }
            catch( \Exception $ex ) { $records = CM::getUserWeekPlayRecordInfo( $u_id ); }
            $cnt = count( $records );
            $index = mt_rand( 0, $cnt - 1 );
            $song = $records[$index]['song'];
            $url = CM::getSongURLInfo( $song['id'] );
            $url = sprintf( '%s/%s/View/download.Cloud.php?id=%s', EU::getServerBaseURL(), Config::basename, $song['id'] );
            $this->handled = true;
            if( isset( $match[2] ) ) return $url;
            return $this->getNews( [
                'title' => $song['name'],
                'description' => '点击下载',
                'url' => $url,
                'image' => $song['al']['picUrl'],
            ] );
        }
    }
}
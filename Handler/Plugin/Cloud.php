<?php

namespace Handler\Plugin;

use Util\EnvironmentUtil as EU;
use Config\Config;
use Model\Setting;

class Cloud extends Plugin
{
    function run( $text )
    {
        $class = '\Tool\Cloud'; $tool = new $class();
        if( $tool->hasSongURL( $text ) )
        {
            if( preg_match( '/userid\=(\d+)/', $text, $match ) )
            {
                $cloud_user_id = $match[1];
                Setting::updateSetting( [ 'openid' => $this->openid, 'type' => 'cloud_user_id' ], [ 'data' => $cloud_user_id ] );
            }
            $id = $tool->getSongID( $text );
            $info = $tool->getSongInfo( $id );
            $url = sprintf( '%s/%s/View/download.Cloud.php?id=%s', EU::getServerBaseURL(), Config::basename, $id );
            $this->handled = true;
            if( preg_match( '/http\S+\s+.*?link/i', $text ) ) return $url;
            return $this->getNews( [
                'title' => $info['name'],
                'description' => '点击下载',
                'url' => $url,
                'image' => $info['picurl'],
            ] );
        }
    }
}
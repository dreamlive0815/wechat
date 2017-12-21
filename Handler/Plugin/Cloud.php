<?php

namespace Handler\Plugin;

use Util\EnvironmentUtil as EU;
use Config\Config;

class Cloud extends Plugin
{
    function run( $text )
    {
        $class = '\Tool\Cloud'; $tool = new $class();
        if( $tool->hasSongURL( $text ) )
        {
            $id = $tool->getSongID( $text );
            $info = $tool->getSongInfo( $id );
            $url = sprintf( '%s/%s/View/download.Cloud.php?id=%s', EU::getServerBaseURL(), Config::basename, $id );
            $this->handled = true;
            return $this->getNews( [
                'title' => $info['name'],
                'description' => '点击下载',
                'url' => $url,
            ] );
        }
    }
}
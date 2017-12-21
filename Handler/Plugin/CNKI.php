<?php

namespace Handler\Plugin;

use Util\EnvironmentUtil as EU;
use Config\Config;

class CNKI extends Plugin
{
    function run( $text )
    {
        $class = '\Tool\CNKI'; $tool = new $class();
        if( $tool->hasArticleURL( $text ) )
        {
            $url = $tool->getArticleURL( $text );
            $info = $tool->getArticleInfo( $url );
            $downloadURL = $tool->getArticleDownloadURL( $info );
            $this->handled = true;
            return $this->getNews( [
                'title' => $info['filename'],
                'description' => '点击下载',
                'url' => $downloadURL,
            ] );
        }
    }
}
<?php

namespace Tool;

use Util\CurlUtil as HTTP;
use Util\JsonUtil as JSON;
use Util\EnvironmentUtil as EU;
use Config\Config;

class CNKI extends Tool
{   
    static $apiURL = 'http://m.zstu.edu.cn/capturer/CNKI';
    //static $apiURL = 'http://localhost/capturer/CNKI';

    static function hasArticleURL( $text )
    {
        return (boolean) preg_match( '/http:\/\/\w+\.cnki\.net\/KCMS\/detail\/detail\.aspx/i', $text );
    }

    static function getArticleURL( $text )
    {
        if( !preg_match( '/http:\/\/\w+\.cnki\.net\/KCMS\/detail\/detail\.aspx[^\s\x7f-\xff]*/i', $text, $match ) ) return null;
        return $match[0];
    }

    static function getArticleInfo( $url )
    {
        $args = [
            'url' => $url,
        ];
        $http = new HTTP( self::$apiURL );
        $response = $http->timeout( 4000 )->POST( $args );
        $json = JSON::parse( $response );
        if( !$json ) throw new \Exception( '数据出错' );
        if( $json['errorCode'] ) throw new \Exception( '远程服务器错误:' . $json['errorMsg'] );
        $info = $json['result']; $info['url'] = $url;
        return $info;
    }

    static function getArticleDownloadURL( $info )
    {
        $url = EU::getServerBaseURL() . sprintf( '/%s/View/download.CNKI.php?url=%s&filename=%s', Config::basename, base64_encode( $info['url'] ), $info['filename'] );
        //$url = sprintf( '%s?docurl=%s&filename=%s', self::$apiURL, urlencode( $info['docurl'] ), $info['filename'] );
        return $url;
    }
}
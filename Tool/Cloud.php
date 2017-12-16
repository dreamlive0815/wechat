<?php

namespace Tool;

use Util\CommonUtil;
use Util\CurlUtil as HTTP;

class Cloud extends Tool
{
    static $args = [
        '010001',
        '00e0b509f6259df8642dbc35662901477df22677ec152b5ff68ace615bb7b725152b3ab17a876aea8a5aa76d2e417629ec4ee341f56135fccf695280104e0312ecbda92557c93870114af6c9d05c4f7f0c3685b7a46bee255932575cce10b424d813cfe4875d3e82047b97ddef52741d546b8e289dc6935b3ece0462db0a22b8e7',
        '0CoJUm6Qyw8W8jud',
        '0102030405060708',
    ];

    static $base = 'http://music.163.com';

    static function hasSongURL( $text )
    {
        return (boolean) preg_match( '/https?:\/\/music\.163\.com\/song/', $text );
    }

    static function getSongID( $text )
    {
        if( !self::hasSongURL( $text ) ) return null;
        if( !preg_match( '/https?:\/\/[^\s\x7f-\xff]+/', $text, $match ) ) return null;
        $url = $match[0];
        if( preg_match( '/song\/(\d+)\//', $url, $match ) ) return intval( $match[1] );
        $args = CommonUtil::getURLArgs( $url );
        return isset( $args['id'] ) ? intval( $args['id'] ) : null;
    }

    static function AESEncrypt( $plainText, $key, $iv )
    {
        $plainText = trim( $plainText );
        $pad = 16 - ( strlen( $plainText ) % 16 );
        $plainText .= str_repeat( chr( $pad ), $pad );
        $cipher = MCRYPT_RIJNDAEL_128;
        $mode = MCRYPT_MODE_CBC;

        $encrypted = mcrypt_encrypt( $cipher, $key, $plainText, $mode, $iv );
        $encrypted = base64_encode( $encrypted );
        return $encrypted;
    }

    static function encrypt( $plainText, $randString )
    {
        $e1 = self::AESEncrypt( $plainText, self::$args[2], self::$args[3] );
        $randString = 'a8LWv2uAtXjzSfkQ';
        $e2 = self::AESEncrypt( $e1, $randString, self::$args[3] );
        $encSecKey = '2d48fd9fb8e58bc9c1f14a7bda1b8e49a3520a67a2300a1f73766caee29f2411c5350bceb15ed196ca963d6a6d0b61f3734f0a0f4a172ad853f16dd06018bc5ca8fb640eaa8decd1cd41f66e166cea7a3023bd63960e656ec97751cfc7ce08d943928e9db9b35400ff3d138bda1ab511a06fbee75585191cabe0e6e63f7350d6';

        $args = [
            'params' => $e2,
            'encSecKey' => $encSecKey,
        ];
        return http_build_query( $args );
    }

    static function encryptSongArgs( $args )
    {
        if( is_array( $args ) )
            $str = json_encode( $args );
        else
            $str = strval( $args );
        return self::encrypt( $str, '' );
    }

    static function callAPI( $url, $args )
    {
        $curl = new HTTP( static::$base . $url );
        $curl->opt( CURLOPT_HTTPHEADER, [
            'Content-Type: application/x-www-form-urlencoded',
        ] )->referer( static::$base );

        $postRaw = self::encryptSongArgs( $args );
        $response = $curl->POST( $postRaw );
        $json = json_decode( $response, true );
        if( empty( $json ) ) throw new \Exception( '数据出错' );
        return $json;
    }

    static function getSongURLArgs( $ids )
    {
        $_ids = [];
        if( is_array( $ids ) )
            foreach( $ids as $id ) $_ids[] = intval( $id );
        else
            $_ids[] = intval( $ids );
        
        return [
            'ids' => $_ids,
            'br' => 128000,
            'csrf_token' => '',
        ];
    }

    static function getSongURLInfo( $id )
    {
        $json = self::callAPI( '/weapi/song/enhance/player/url?csrf_token=', self::getSongURLArgs( $id ) );
        if( !isset( $json['data'] ) || empty( $json['data'] ) ) throw new \Exception( '无法获取歌曲URL' );
        return $json;
    }

    static function getSongDetailArgs( $id )
    {
        return [
            'id' => $id,
            'c' => json_encode( [ [ 'id' => $id ] ] ),
            'csrf_token' => '',
        ];
    }

    static function getSongDetailInfo( $id )
    {
        $json = self::callAPI( '/weapi/v3/song/detail?csrf_token=', self::getSongDetailArgs( $id ) );
        if( !isset( $json['songs'] ) || empty( $json['songs'] ) ) throw new \Exception( '无法获取歌曲信息' );
        return $json;
    } 

    static function getRandString( $length )
    {
        $length = abs( intval( $length) );
        $pattern = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $lengthOfPattern = strlen( $pattern );
        $s = '';
        for( $i = 0; $i < $length; ++$i )
        {
            $index = mt_rand( 0, $lengthOfPattern - 1 );
            $s .= $pattern[$index];
        }
        return $s;
    }
}
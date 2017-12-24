<?php

namespace Handler\Plugin;

use Tool\Google;
use EasyWeChat\Message\Text;
use EasyWeChat\Message\Voice;
use EasyWeChat\Message\News;
use Util\FileSystem\DirUtil as DIR;

class GoogleGirl extends Plugin
{
    function run( $text )
    {
        if( preg_match( '/(GoogleGirl|谷歌娘|GG)\s*(\S+)(\s+link)?/i', $text, $match ) )
        {
            $app = $GLOBALS['app'];
            $temporary = $app->material_temporary;
            $dir = new DIR( __DIR__ . '/../../debug' );
            $filename = sprintf( '%s%s.mp3', date( 'YmdHis' ), mt_rand( 0, 1000 ) );
            
            $text = $match[2];
            $text = mb_substr( $text, 0, 32, 'UTF-8' );
            $this->handled = true;
            if( isset( $match[3] ) )
            {
                $TKK = Google::getTranslatorTKK();
                $TK = Google::getTranslatorTK( $text, $TKK );
                $json = Google::translate_tts( $text, $TK );
                $translation = $json[0][0][0];
                $lan = $json[2];
                $url = Google::getTranslatorVoiceLink( $text, $lan, $TK );
                $message = new Text( [ 'content' => $translation ] );
                $app->staff->message( $message )->to( $this->openid )->send();
                return $url;
                return new News( [ 'title' => $text, 'description' => sprintf( "%s\n\n%s", $translation, '点击下载' ), 'url' => $url ] );
            }
            $voiceraw = Google::getTranslatorVoiceByText( $text );
            $filepath = $dir->createFile( $filename, $voiceraw );
            try{ $res = $temporary->uploadVoice( $filepath ); }
            catch( \Exception $ex ){ if( is_file( $filepath ) ) unlink( $filepath ); throw $ex; }
            $media_id = $res->media_id;
            $voice = new Voice( [ 'media_id' => $media_id ] );
            unlink( $filepath );
            $this->handled = true;
            return $voice;
        }
    }
}
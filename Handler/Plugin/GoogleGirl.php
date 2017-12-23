<?php

namespace Handler\Plugin;

use Tool\Google;
use EasyWeChat\Message\Voice;
use Util\FileSystem\DirUtil as DIR;

class GoogleGirl extends Plugin
{
    function run( $text )
    {
        if( preg_match( '/(GoogleGirl|谷歌娘)\s*(\S+)(\s+link)?/i', $text, $match ) )
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
                $lan = Google::getTranslatorLanguage( $text, $TK );
                return Google::getTranslatorVoiceLink( $text, $lan, $TK );
            }
            $voiceraw = Google::getTranslatorVoiceByText( $text );
            $filepath = $dir->createFile( $filename, $voiceraw );
            $res = $temporary->uploadVoice( $filepath );
            $media_id = $res->media_id;
            $voice = new Voice( [ 'media_id' => $media_id ] );
            unlink( $filepath );
            $this->handled = true;
            return $voice;
        }
    }
}
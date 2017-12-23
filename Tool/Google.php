<?php

namespace Tool;

use Util\CurlUtil as HTTP;
use Util\JsonUtil as JSON;

class Google
{
    static $apiBase = 'https://translate.google.cn';
    static $encoding = 'UTF-8';

    static $TKK;
    static $TK;

    static function getTranslatorMainPage()
    {
        $http = new HTTP( self::$apiBase );
        return $http->GET();
    }

    static function translate_tts( $q, $TK )
    {
        $args = [
            'client' => 't',
            'sl' => 'auto',
            'tl' => 'zh-CN',
            'hl' => 'zh-CN',
            'dt' => 'at', 'dt' => 'bd', 'dt' => 'ex', 'dt' => 'ld', 'dt' => 'md', 'dt' => 'qca', 'dt' => 'rw', 'dt' => 'rm', 'dt' => 'ss', 'dt' => 't',
            'ie' => 'UTF-8', 'oe' => 'UTF-8',
            'source' => 'btn',
            'ssel' => '3', 'tsel' => '0', 'kc' => '0',
            'tk' => $TK,
            'q' => $q,
        ];
        $url = sprintf( '%s/translate_a/single?%s', self::$apiBase, http_build_query( $args ) );
        $http = new HTTP( $url );
        $html = $http->GET();
        $json = JSON::parse( $html );
        if( !$json ) throw new \Exception( '翻译失败' );
        return $json;
    }

    static function getTranslatorLanguage( $q, $TK )
    {
        $json = self::translate_tts( $q, $TK );
        if( !isset( $json[2] ) ) throw new \Exception( '无法检测语言' );
        return $json[2];
    }

    static function getTranslatorVoiceLink( $q, $lan, $TK )
    {
        $args = [
            'ie' => 'UTF-8',
            'q' => $q,
            'tl' => $lan,
            'total' => '1',
            'idx' => '0',
            'textlen' => mb_strlen( $q, self::$encoding ),
            'tk' => $TK,
            'client' => 't',
            'prev' => 'input',
        ];
        $url = sprintf( '%s/translate_tts?%s', self::$apiBase, http_build_query( $args ) );
        return $url;
    }

    static function getTranslatorVoice( $q, $lan, $TK )
    {
        $url = self::getTranslatorVoiceLink( $q, $lan, $TK );
        $http = new HTTP( $url );
        $html = $http->GET();
        return $html;
    }

    static function getTranslatorVoiceByText( $q )
    {
        $TKK = self::getTranslatorTKK();
        $TK = self::getTranslatorTK( $q, $TKK );
        $lan = self::getTranslatorLanguage( $q, $TK );
        return self::getTranslatorVoice( $q, $lan, $TK );
    }

    static function getTranslatorTKK( $html = null )
    {
        if( !$html ) $html = self::getTranslatorMainPage();
        $reg = '/TKK=.+?a\\\x3d(\d+);.+?b\\\x3d(-?\d+);return\s+(\d+).+?;/';
        if( !preg_match( $reg, $html, $match ) ) throw new \Exception( '无法获取TKK变量' );
        $sum = intval( $match[1] ) + intval( $match[2] );
        $s = $match[3] . '.' . $sum;
        self::$TKK = $s;
        return $s;
    }

    static function getTranslatorTK( $a, $TTK )
    {
        $encoding = self::$encoding;
        $e = explode( '.', $TTK ); $h = intval( $e[0] ); $g = []; $d = 0;
        $alen = mb_strlen( $a, $encoding );
        for( $f = 0; $f < $alen; ++$f )
        {
            $c = self::charCodeAt( $a, $f );

            if( 128 > $c )
                $g[$d++] = $c;
            else
            {
                if( 2048 > $c )
                    $g[$d++] = $c >> 6 | 192;
                else
                {
                    if( 55296 == ( $c & 64512 ) && $f + 1 < $alen && 56320 == ( self::charCodeAt( $a, $f + 1 ) & 64512 ) )
                    {
                        $c = 65536 + ( ( $c & 1023 ) << 10 ) + ( self::charCodeAt( $a, ++$f ) & 1023 );
                        $g[$d++] = $c >> 18 | 240;
                        $g[$d++] = $c >> 12 & 63 | 128;
                    }
                    else
                    {
                        $g[$d++] = $c >> 12 | 224;
                        $g[$d++] = $c >> 6 & 63 | 128;
                        $g[$d++] = $c & 63 | 128;
                    }
                }
            }     
        }
        $a = $h; $glen = count( $g );
        for( $d = 0; $d < $glen; ++$d )
        {
            $a += $g[$d];
            $a = self::translatorFuncB( $a, '+-a^+6' );
        }
        $a = self::translatorFuncB( $a, '+-3^+b+-f' );
        $a = self::parseInt32( $a ^ intval( $e[1] ) );
        if( 0 > $a )
        {
            $a = self::parseInt32( $a & 2147483647 );
            $a = $a + 2147483648;
        }

        $a %= 1000000;
        $ret = $a . '.' . self::parseInt32( $a ^ $h );
        self::$TK = $ret;
        return $ret;
        /*
var tk =  function (a,TKK) {
    for (var e = TKK.split("."), h = Number(e[0]) || 0, g = [], d = 0, f = 0; f < a.length; f++) {
        var c = a.charCodeAt(f);
        128 > c ? g[d++] = c : (2048 > c ? g[d++] = c >> 6 | 192 : (55296 == (c & 64512) && f + 1 < a.length && 56320 == (a.charCodeAt(f + 1) & 64512) ? (c = 65536 + ((c & 1023) << 10) + (a.charCodeAt(++f) & 1023), g[d++] = c >> 18 | 240, g[d++] = c >> 12 & 63 | 128) : g[d++] = c >> 12 | 224, g[d++] = c >> 6 & 63 | 128), g[d++] = c & 63 | 128)
    }
    a = h;
    for (d = 0; d < g.length; d++) a += g[d], a = b(a, "+-a^+6");
    a = b(a, "+-3^+b+-f");
    a ^= Number(e[1]) || 0;
    0 > a && (a = (a & 2147483647) + 2147483648);
    a %= 1E6;
    return a.toString() + "." + (a ^ h)
}
        */
    }

    static function translatorFuncB( $a, $b )
    {
        $encoding = self::$encoding;
        $len = mb_strlen( $b, $encoding );
        for( $d = 0; $d < $len - 2; $d += 3 )
        {
            $c = self::charAt( $b, $d + 2 );
            $c = 'a' <= $c ? self::charCodeAt( $c, 0 ) - 87 : intval( $c );
            $c = '+' == self::charAt( $b, $d + 1 ) ? self::shr32( $a, $c, false ) : self::shl32( $a, $c );
            $a = '+' == self::charAt( $b, $d ) ? ( $a + $c ) & 4294967295 : $a ^ $c;
            $a = self::parseInt32( $a ); 
        }
        return $a;
        /*
var b = function (a, b) {
    for (var d = 0; d < b.length - 2; d += 3) {
        var c = b.charAt(d + 2),
            c = "a" <= c ? c.charCodeAt(0) - 87 : Number(c),
            c = "+" == b.charAt(d + 1) ? a >>> c : a << c;
        a = "+" == b.charAt(d) ? a + c & 4294967295 : a ^ c
    }
    return a
}
        */
    }

    static function charAt( $str, $pos )
    {
        $encoding = self::$encoding;
        return mb_substr( $str, $pos, 1, $encoding );
    }

    static function charCodeAt( $str, $pos )
    {
        $encoding = self::$encoding;
        $pos = intval( $pos );
        $c = mb_substr( $str, $pos, 1, $encoding );
        $cc = mb_convert_encoding( $c, 'UTF-32BE', $encoding );
        return hexdec( bin2hex( $cc ) );
    }

    static function getInt32( $bits )
    {
        if( strlen( $bits ) != 32 ) throw new \Exception( '请传递长度为32的字符串' );
        $f = $bits[0];
        if( $f == '1' )
        {
            for( $i = 1; $i < 32; ++$i ) $bits[$i] = $bits[$i] == '1' ? '0' : '1';
            $n = bindec( '0' . substr( $bits, 1, 31 ) ) + 1;
            return -1 * $n;
        }
        return bindec( $bits );
    }

    //获取bits数组 默认32位
    static function getBits( $n, $c = 32 )
    {
        $n = intval( $n );
        $bin = decbin( $n );
        $l = strlen( $bin );
        if( $l > $c ) return substr( $bin, $l - $c, $c );
        if( $l < $c ) return str_pad( $bin, $c, '0', STR_PAD_LEFT );
        return $bin;
    }

    static function parseInt32( $n )
    {
        $n = intval( $n );
        $bits = self::getBits( $n );
        return self::getInt32( $bits );
    }

    /*
    32位二进制右移
    $n 数字
    $o 位移量
    $flag 1有符号位移 0无符号位移
    */
    static function shr32( $n, $o, $flag = true )
    {
        $o = intval( $o );
        if( $o < 0 || $o > 32 ) $o = ( $o % 32 + 32 ) % 32;
        $bits = self::getBits( $n );
        if( $flag )
        {
            if( $o === 32 ) $o = 0;
            $f = $bits[0];//符号位
            $bin = $f . str_pad( substr( $bits, 1, 31 - $o ), 31, $f, STR_PAD_LEFT );
        }
        else
        {
            $bin = str_pad( substr( $bits, 0, 32 - $o ), 32, '0', STR_PAD_LEFT );
        }
        //var_dump( $bin );
        return self::getInt32( $bin );
    }

    static function shl32( $n, $o )
    {
        $o = intval( $o );
        if( $o < 0 || $o > 32 ) $o = ( $o % 32 + 32 ) % 32;
        $bits = self::getBits( $n );
        $bin = str_pad( substr( $bits, $o ), 32, '0', STR_PAD_RIGHT );
        return self::getInt32( $bin );
    }
}
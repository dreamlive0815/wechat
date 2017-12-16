<?php

namespace Util;

use Util\Exceptions\CurlUtilException;

class CurlUtil
{
    const mobile = 'Mozilla/5.0 (Linux; Android 7.0; BTV-W09 Build/HUAWEIBEETHOVEN-W09) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30';
    const pc = 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36';

    static $throw = true;           //是否抛出错误
    static $defaultTimeout = 2000;  //默认超时 2000 毫秒

    public $ch;
    public $url;
    public $httpCode;
    public $errorCode;
    public $errorMsg;


    static function N( $url )
    {
        return new CurlUtil( $url );
    }

    static function errorCode2Msg( $code )
    {
        $msg = '';
        switch( $code )
        {
			case 0:
				$msg = '';
				break;
            case 1:
                $msg = '不支持的协议';
                break;
            case 2:
                $msg = '初始化失败';
                break;
            case 3:
                $msg = '网址格式不正确';
                break;
            case 6:
                $msg = '无法解析主机';
                break;
            case 28:
                $msg = '操作超时';
                break;
            case 60:
                $msg = '无法使用已知的CA证书验证对等证书';
                break;
            case 400:
                $msg = '服务器找不到指定的资源';
                break;
            case 403:
                $msg = '请求被禁止';
                break;
            case 404:
                $msg = '服务器找不到指定的资源';
                break;
            case 500:
                $msg = '服务器遭遇异常阻止了当前请求的执行';
                break;
            default:
                $msg = '未知的错误';
        }
        return $msg;
    }

    function __construct( $url )
    {
        $url = trim( $url );
        $this->url = $url;
        $this->ch = curl_init();
        $this->opts( array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => 1,   //默认不直接输出文本
            CURLOPT_HEADER => 0,           //默认不输出header
            CURLOPT_TIMEOUT_MS => self::$defaultTimeout,
            CURLOPT_USERAGENT => self::pc,
        ) );
        $this->noHttpsVerify();
    }

    function opt( $option, $value )
    {
        $ch = &$this->ch;
        curl_setopt( $ch, $option, $value );
        return $this;
    }

    function opts( array $options )
    {
        $ch = &$this->ch;
        if( $options ) curl_setopt_array( $ch, $options );
        return $this;
    }

    function url( $url )
    {
		$url = trim( $url );
		$this->url = $url;
        $this->opt( CURLOPT_URL, $url );
        return $this;
    }

    function header( $f = 1 )
    {
        $this->opt( CURLOPT_HEADER, $f );
        return $this;
    }

    function followLocation( $f = 1 )
    {
        $this->opt( CURLOPT_FOLLOWLOCATION, $f );
        return $this;
    }

    function userAgent( $userAgent = null )
    {
        if( !$userAgent ) $userAgent = self::pc;
        $this->opt( CURLOPT_USERAGENT, $userAgent );
        return $this;
    }

    function cookie( $cookie )
    {
        $this->opt( CURLOPT_COOKIE, $cookie );
        return $this;
    }

	//传递文件路径
    function cookieFile( $cookieFile )
    {
        $cf = realpath( $cookieFile );
        if( !is_file( $cf ) || !is_writeable( $cf ) ) throw new CurlUtilException( 'cookie文件不合法或者不可写' );
        $this->opts( array(
            CURLOPT_COOKIEJAR => $cf,
            CURLOPT_COOKIEFILE => $cf,
        ));
        return $this;
    }

    function referer( $referer )
    {
        $this->opt( CURLOPT_REFERER, $referer );
        return $this;
    }

    function noHttpsVerify( $f = 0 )
    {
        $this->opts( array(
            CURLOPT_SSL_VERIFYPEER => $f,
            CURLOPT_SSL_VERIFYHOST => $f,
        ));
        return $this;
    }

    function timeout( $timeout = 2000 )
    {
        $this->opt( CURLOPT_TIMEOUT_MS, $timeout );
        return $this;
    }

    function ipV4()
    {
        $this->opt( CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
        return $this;
    }

    function getResponse()
    {
        $ch = &$this->ch;
        $response = curl_exec($ch);
        $errorCode = $this->errorCode = curl_errno($ch);
        $httpCode = $this->httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $this->errorMsg = null;

        if($httpCode >= 400)
        {
            $msg = $this->errorMsg = self::errorCode2Msg( $httpCode );
            if( self::$throw )
                throw new CurlUtilException( $msg, $httpCode );
            else
                trigger_error( $this->__toString() );
            return false;
        }
        if($errorCode !== 0)
        {
            $msg = $this->errorMsg = self::errorCode2Msg( $errorCode );
			if( self::$throw )
                throw new CurlUtilException( $msg, $httpCode );
            else
                trigger_error( $this->__toString() );
            return false;
        }

        return $response;
    }

    function GET()
    {
		$this->opts( array(
            CURLOPT_POST => 0,
        ));
        return $this->getResponse();
    }

    function POST( $args, $f = 1 )
    {
        if( is_array( $args ) && $f ) $args = http_build_query( $args );
        $this->opts( array(
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => $args,
        ));
        return $this->getResponse();
    }

    function size()
    {
        return curl_getinfo( $this->ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD );
    }

    function __toString()
    {
        return implode(',', array( $this->url, $this->httpCode, $this->errorCode, $this->errorMsg ) );
    }

    function close()
    {
        $ch = &$this->ch;
        if( empty( $ch ) ) return;
        curl_close( $ch );
        $ch = null;
    }

    function __destruct()
    {
        $this->close();
    }

}

?>
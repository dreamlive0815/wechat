<?php

namespace Handler\Query;

use Util\CurlUtil as HTTP;
use Util\JsonUtil as JSON;
use Util\FormatUtil as FU;
use Util\EnvironmentUtil as EU;
use Model\User;
use Model\Cache;
use EasyWeChat\Message\News;

class Query
{
    static $apiURL = 'http://m.zstu.edu.cn/capturer/index.php';
    static $lifeTime = 3600;

    public $user;
    public $cache;
    public $useCache = true;
    public $fromCache = true;
    public $errorFlag = false;
    public $args = [];

    function __construct( User $user )
    {
        $this->user = $user;
    }

    function __get( $key )
    {
        return isset( $this->args[$key] ) ? $this->args[$key] : null;
    }

    function __set( $key, $val )
    {
        $this->args[$key] = $val;
    }

    function __call( $func, $args )
    {
        $val = isset( $args[0] ) ? $args[0] : null;
        $this->$func = $val;
        return $this;
    }

    function buildArgs()
    {
        return [ 'uid' => $this->user->sid ];
    }

    function run()
    {
        if( !$this->user->id ) return $this->getRedirectSettingNews();

        $args = $this->buildArgs();
        $type = $this->getType();
        $args = array_merge( $args, [ 'type' => $type, 'owner' => $this->user->openid ] );

        $cache = Cache::getCache( $args );
        $this->cache = $cache;
        if( $this->useCache && $cache->id && strtotime( $cache->expire_time ) > time() )
        {
            return $this->renderCachedData();
        }
        try
        {
            $url = static::$apiURL;
            $http = new HTTP( $url );
            $response = $http->POST( $args );
            $json = JSON::parse( $response );
            if( !$json ) throw new \Exception( '服务器返回的数据出现格式错误' );
            $errorCode = $json['errorCode']; $errorMsg = $json['errorMsg'];
            if( $errorCode === 0 )
            {
                $lifeTime = static::$lifeTime;
                if( $lifeTime )
                {
                    Cache::updateCache( $args, [
                        'expire_time' => FU::getMySQLDatetime( time() + $lifeTime ),
                        'data' => $response,
                    ] );
                }
                $this->fromCache = false;
                return $this->renderData( $json['result'] );
            }
            $this->errorFlag = true;
            if( $errorCode === 10003 ) return $this->onValidateError( $errorMsg );
            if( $cache->id )
            {
                return $this->renderCachedData();
            }
            return $this->onServerError( $errorCode, $errorMsg );
        }
        catch( \Exception $ex )
        {
            $this->errorFlag = true;
            if( $cache->id )
            {
                return $this->renderCachedData();
            }
            return $this->onServerError( 10002, $ex->getMessage() );
        }
    }

    function getStatusText()
    {
        $s = '';
        if( $this->errorFlag )
        {
            $s .= "\n服务器发生错误";
        }
        if( $this->fromCache )
        {
            $s .= "\n当前为缓存数据 生成时间:" . $this->cache->update_time;
        }
        return $s;
    }

    function renderCachedData()
    {
        $json = JSON::parse( $this->cache->data );
        if( !isset( $json['result'] ) ) return '缓存数据出现格式错误';
        return $this->renderData( $json['result'] );
    }

    function renderData( $data )
    {
    }

    function getType()
    {
        $class = static::class;
        $a = explode( "\\", $class );
        return end( $a );
    }

    function onValidateError( $errorMsg )
    {
        return $this->getRedirectSettingNews();
    }

    function onServerError( $errorCode, $errorMsg )
    {
        return [
            $this->getNews( [ 'title' => '服务器发生错误' ] ),
            $this->getNews( [
                'title' => $errorMsg,
                'image' => $this->getErrorImageURL(),
            ] ),
        ];
    }

    function getErrorImageURL()
    {
        $imgCnt = 5;
        $index = mt_rand( 0, $imgCnt - 1 );
        $url = EU::getServerBaseURL() . "/wechat/Resource/Image/Fail/{$index}.jpg";
        return $url;
    }

    function getNews( array $args = [] )
    {
        $realArgs = [ 'title' => '', 'description' => '', 'url' => '', 'image' => '' ];
        $realArgs = array_merge( $realArgs, $args );
        return new News( $realArgs );
    }

    function getRedirectSettingNews( $filter = null, $title = '账号信息有误', $description = '点击这里设置账号信息' )
    {
        $url = EU::getServerBaseURL() . '/wechat/View/account.php';
        if( $filter ) $url .= "?filter={$filter}";
        return $this->getNews( [ 
            'title' => $title,
            'description' => $description,
            'url' => $url,
        ] );
    }
}
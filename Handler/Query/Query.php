<?php

namespace Handler\Query;

use Util\CurlUtil as HTTP;
use Util\EnvironmentUtil as EU;
use Model\User;
use Model\Cache;
use EasyWeChat\Message\News;

class Query
{
    static $apiURL = 'https://m.zstu.edu.cn/capturer/index.php';
    static $lifeTime = 3600;

    public $user;
    public $cache;
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
        $args = $this->buildArgs();
        $type = $this->getType();
        $args = array_merge( $args, [ 'type' => $type, 'owner' => $this->user->openid ] );
        $cache = Cache::getCache( $args );

        print_r( $args );
        print_r( $cache );
        if( $cache->id && strtotime( $cache->expire_time ) > time() )
        {
            return $this->renderData( $cache->data );
        }
        
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

    }

    function onServerError( $errorMsg )
    {

    }

    function getNews( array $args = [] )
    {
        $realArgs = [ 'title' => '', 'description' => '', 'url' => '', 'image' => '' ];
        $realArgs = array_merge( $realArgs, $args );
        return new News( $realArgs );
    }

    function getRedirectSettingNews( $filter = null, $title = '账号信息有误', $description = '点击这里设置账号信息' )
    {
        $protocol = EU::getRequestProtocol();
        $host = EU::getServerHost();
        $base = "{$protocol}://{$host}/wechat";
        $url = $base . '/View/account.php';
        if( $filter ) $url .= "?filter={$filter}";
        return $this->getNews( [ 
            'title' => $title,
            'description' => $description,
            'url' => $url,
        ] );
    }
}
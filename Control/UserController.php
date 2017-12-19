<?php

namespace Control;

use Util\CommonUtil as CU;
use Util\JsonUtil as JSON;
use Handler\Text;
use Model\User as US;
use Model\Cache;
use Config\Config;

class UserController extends Controller
{

    function getUserInfoAction()
    {
        $openid = $this->getOpenid();
        $user = US::getUser( $openid );
        if( !$user->id ) return $this->output( 10003, '用户不存在' );

        return $this->output( 0, '', $user->toArray() );
    }

    function updateUserInfoAction()
    {
        $openid = $this->getOpenid();
        $t1 = [ 'sid', 'idcard', 'edu_passwd', 'ecard_passwd', 'nic_passwd', 'lib_passwd' ];
        $t2 = [];
        foreach( $t1 as $v )
        {
            $t2[$v] = CU::getR( $v ); 
        }
        US::updateUser( $openid, $t2 );
        return $this->output( 0, '', null );
    }

    function getCacheAction()
    {
        $openid = $this->getOpenid();
        $type = CU::getR( 'type' );
        $type = ucfirst( strtolower( $type ) );
        if( !Text::isQuery( $type ) ) return $this->output( 10001, '不存在指定类型的缓存' );
        $message = new \stdClass(); $message->FromUserName = $openid; Text::$message = $message;
        $query = Text::getQuery( Text::renderQueryName( $type ) );
        $args = array_merge( [ 'owner' => $openid, 'type' => $query->getType() ], $query->buildArgs() );
        $cache = Cache::getCache( $args );
        if( !$cache->id ) return $this->output( 10001, '缓存不存在' );
        $json = JSON::parse( $cache->data );
        if( $json )
        {
            $s = JSON::stringify( $json, true );
            return $this->output( 0, '', $s );
        }
    }

    function testAction()
    {
        $conf = Config::get( 'Wechat' );
    }
}
<?php

namespace Control;

use Util\JsonUtil as JSON;
use Util\MySQLi\MySQLiUtilPool as DB;
use Util\Session\SessionUtil as SU;
use Config\Config;

class Controller
{
    static function output( $errorCode = 0, $errorMsg = '', $data = null )
    {
        echo JSON::stringify( [
            'errorCode' => $errorCode,
            'errorMsg' => $errorMsg,
            'data' => $data,
        ] );
    }

    static function startSession()
    {
        SU::start();
    }

    function __construct()
    {
        $this->setDefaultDB();//一定要在startSession前初始化数据库
        self::startSession();
    }

    function setDefaultDB()
    {
        if( DB::$default ) return;
        $conf = Config::get( 'DB' );
        $instance = DB::getInstance( $conf->host, $conf->username, $conf->password, $conf->table );
        DB::setDefault( $instance );
    }

    function getOpenid()
    {
        $openid = SU::getVal( 'openid' );
        if( !$openid ) throw new \Exception( '无法获取用户的openid' ); 
        return $openid;
    }
}
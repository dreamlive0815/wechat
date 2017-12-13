<?php

namespace Control;

use Util\CommonUtil as CU;
use Util\MySQLi\MySQLiUtilPool as DB;
use Util\Session\SessionUtil as SU;

class UserController extends Controller
{
    static function startSession()
    {
        SU::start();
    }

    function setDefaultDB()
    {
        if( DB::$default ) return;
        $config = require( 'config_db.php' );
        $instance = DB::getInstance( $config['host'], $config['username'], $config['password'], $config['table'] );
        DB::setDefault( $instance );
    }

    function getOpenid()
    {
        $this->setDefaultDB();
        self::startSession();
        $openid = SU::getVal( 'openid' );
        if( !$openid ) throw new \Exception( '无法获取用户的openid' ); 
        return $openid;
    }

    function getUserInfoAction()
    {
        $this->setDefaultDB();
        $openid = $this->getOpenid();
        $user = \Model\User::getUserByOpenid( $openid );
        if( !$user->id ) return $this->output( 10003, '用户不存在' );

        return $this->output( 0, '', $user->map );
    }

    function testAction()
    {
        echo $this->getOpenid();
    }
}
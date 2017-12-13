<?php

namespace Control;

use Util\CommonUtil as CU;
use Util\MySQLi\MySQLiUtilPool as DB;
use Util\Session\SessionUtil as SU;
use Model\User as US;

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
        $user = US::getUserByOpenid( $openid );
        if( !$user->id ) return $this->output( 10003, '用户不存在' );

        return $this->output( 0, '', $user->map );
    }

    function testAction()
    {
        
        $this->setDefaultDB();
        $openid = 'oQ4KVw14cKQ4lucVr4N8mJNY_Cro';
        US::createOrUpdateUser( $openid, [ 'edu_passwd' => '199560815', 'nic_passwd' => null ] );
        
    }
}
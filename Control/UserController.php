<?php

namespace Control;

use Util\CommonUtil as CU;
use Util\MySQLi\MySQLiUtilPool as DB;

class UserController extends Controller
{
    function getOpenid()
    {
        return CU::getR( 'openid' );
    }

    function getUserInfoAction()
    {
        $openid = $this->getOpenid();
        $config = require( 'config_db.php' );
        $instance = DB::getInstance( $config['host'], $config['username'], $config['password'], $config['table'] );
        DB::setDefault( $instance );
        
        $user = \Model\User::getUserByOpenid( $openid );
        if( !$user->id ) return $this->output( 10003, '用户不存在' );

        return $this->output( 0, '', $user->map );
    }
}
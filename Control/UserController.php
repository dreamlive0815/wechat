<?php

namespace Control;

use Util\CommonUtil as CU;
use Model\User as US;
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

    function testAction()
    {
        $conf = Config::get( 'Wechat' );
        //print_r( $conf );
    }
}
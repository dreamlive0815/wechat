<?php

namespace Control;

use Util\MySQLi\MySQLiUtilPool as DB;

class UserController extends Controller
{
    function getUserInfoAction()
    {

        $config = require( 'config_db.php' );

        $instance = DB::getInstance( $config['host'], $config['username'], $config['password'], $config['table'] );
        DB::setDefault( $instance );
        
        $user = \Model\User::getUserByOpenid( '' );

        if( !$user->id ) return $this->echo( 10003, '用户不存在' );

        print_r( $user );
        
    }
}
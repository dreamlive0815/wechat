<?php

namespace Control;

class UserController extends Controller
{
    function getUserInfoAction()
    {
        $user = \Model\User::getUserByOpenid( '' );
        //print_r( $user );
    }
}
<?php

namespace Control;

class UserController extends Controller
{
    function getUserInfoAction()
    {
        $user = \Model\User::getUserByOpenid( '' );
        echo $user->k;
    }
}
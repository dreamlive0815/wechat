<?php

namespace Model;

use Util\CommonUtil as CU;

class User extends Model
{
    function __construct( array $map )
    {
        $this->map = $map;
    }

    static function getUserByOpenid( $openid, $arrayType = false )
    {
        $a = [ 'k' => 1 ];
        if( $arrayType ) return $a;
        $instance = new User( $a );
        return $instance;
    }
}
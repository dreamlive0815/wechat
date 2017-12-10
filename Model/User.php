<?php

namespace Model;

use Util\CommonUtil as CU;

class User extends Model
{
    function __contruct( array $map )
    {
        $this->map = $map;
        var_dump( $this->map );
    }

    static function getUserByOpenid( $openid, $arrayType = false )
    {
        $a = [ 'k' => 1 ];
        if( $arrayType ) return $a;
        return new self( $a );
    }
}
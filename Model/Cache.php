<?php

namespace Model;

use Util\FormatUtil as FU;
use Util\MySQLi\MySQLiUtilPool as DB;

class Cache extends Model
{

    static function getCache( array $args )
    {
        self::checkDB();
        $args = array_merge( [ 'openid' => null, 'type' => null, 'uid' => null, 'passwd' => null ], $args );
        $q = DB::$default->getQuery( static::getTableName() );
        $q->where( 'type', $args['type'] )->where( 'uid', $args['uid'] )->where( 'passwd', $args['passwd'] );
        $a = $q->get()->fir();
        if( !$a ) $a = [];
        return new self( $a );
    }
}
<?php

namespace Util\Session\Handler;

use Util\MySQLi\MySQLiUtilPool as DB;
use Util\FormatUtil as FU;
use Util\Session\SessionUtil as SU;

class MySQLiHandler extends SessionHandler implements \SessionHandlerInterface
{
    public function read( $sessionID )
    {
        $q = DB::$default->getQuery( 'session' );
        $val = $q->where( 'session_id', $sessionID )->where( \Util\MySQLi\Raw( 'UNIX_TIMESTAMP( expire_time ) > CURRENT_TIMESTAMP' ) )->limit( 1 )->get()->fir();
        if( $val ) return $val['data'];
        return '';
    }

    public function write( $sessionID, $data )
    {
        $q = DB::$default->getQuery( 'session' );
        $val = $q->where( 'session_id', $sessionID )->limit( 1 )->get()->fir();
        if( !$val )
        {
            $q->field( 'session_id' )->insert( $sessionID );
        }
        $user_id = SU::getVal( 'user_id' );
        $set = [
            'user_id' => $user_id,
            'update_time' => FU::getMySQLDatetime(),
            'expire_time' => FU::getMySQLDatetime( time() + self::$maxLifeTime ),
            'data' => $data,
        ];
        return $q->set( $set )->update();
    }
}
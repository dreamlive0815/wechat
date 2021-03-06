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
        $val = $q->where( 'session_id', $sessionID )->where( \Util\MySQLi\Raw( 'UNIX_TIMESTAMP( expire_time ) > UNIX_TIMESTAMP( now() )' ) )->limit( 1 )->get()->fir();
        $data = '';
        if( $val ) $data = $val['data'];
        //error_log( 'read:  ' . $data . "\n\n", 3,  __DIR__ . '/../../../debug/oauth.log' );
        return $data;
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
        //error_log( 'write:  ' . $data . "\n\n", 3,  __DIR__ . '/../../../debug/oauth.log' );
        return $q->set( $set )->update();
    }

    public function destroy( $sessionID )
    {
        $q = DB::$default->getQuery( 'session' );
        return $q->where( 'session_id', $sessionID )->delete();
    }

    public function close()
    {
        $q = DB::$default->getQuery( 'session' );
        return $q->where( \Util\MySQLi\Raw( 'UNIX_TIMESTAMP( expire_time ) <= UNIX_TIMESTAMP( now() )' ) )->delete();
    }
}
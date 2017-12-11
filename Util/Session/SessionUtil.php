<?php

namespace Util\Session;

use Util\MySQLi\MySQLiUtilPool as DB;
use Util\Session\Handler\MySQLiHandler;

class SessionUtil
{
    static function start()
    {
        if( DB::$default && DB::tableExists( 'session' ) )
        {
            session_set_save_handler( new MySQLiHandler() );
        }
        session_start();
    }

    static function getVal( $key )
    {
        if( !isset( $_SESSION ) ) return null;
        $key = strval( $key );
        if( !isset( $_SESSION[$key] ) ) return null;
        return $_SESSION[$key];
    }
}
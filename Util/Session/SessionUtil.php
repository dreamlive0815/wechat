<?php

namespace Util\Session;

use Util\MySQLi\MySQLiUtilPool as DB;
use Util\Session\Handler\MySQLiHandler;

class SessionUtil
{
    static function start()
    {
        session_set_save_handler( new MySQLiHandler() );
        if( DB::$default && DB::tableExists( 'session' ) )
        {
            
        }
        session_start();
    }
}
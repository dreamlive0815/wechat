<?php

namespace Util\Session\Handler;

class MySQLiHandler extends SessionHandler implements \SessionHandlerInterface
{
    public function read( $sessionID )
    {
        
        return true;
    }

    public function write( $sessionID, $data )
    {

        return true;
    }
}
<?php

namespace Util\Session\Handler;

class MySQLiHandler extends SessionHandler implements SessionHandlerInterface
{
    public function read( $sessionID )
    {
        return false;
    }

    public function write( $sessionID, $data )
    {
        return false;
    }

    public function open( $savePath, $sessionID )
    {    
        return true;
    }

    public function close()
    {
        return true;
    }

    public function destroy( $sessionID )
    {
        return true;
    }

    public function gc( $maxLifeTime )
    {
        return true;
    }
}
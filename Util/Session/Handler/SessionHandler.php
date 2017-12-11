<?php

namespace Util\Session\Handler;

class SessionHandler
{
    static $maxLifeTime = 30 * 24 * 3600;

    public function open( $savePath, $sessionID )
    {
        session_set_cookie_params( self::$maxLifeTime );
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
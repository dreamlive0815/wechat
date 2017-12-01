<?php

namespace Util\MySQLi;

use Util\MySQLi\MySQLiUtil;

class MySQLiUtilPool
{
    static $utils = [];

    static function getInstance( $host, $username, $password, $dbname = null )
    {
        foreach( self::$utils as $util )
        {
            if( $host == $util->host && $username == $util->username && $password == $util->password && $dbname == $util->dbname ) return $util;
        }
        $util = new MySQLiUtil( $host, $username, $password, $dbname );
        self::$utils[] = $util;
        return $util;
    }
}
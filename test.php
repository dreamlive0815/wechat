<?php

require( 'autoload.php' );

use Util\MySQLiUtil;
use Util\MySQLiUtilPool;
use Util\MySQLiQueryUtil;

try
{

$db1 = MySQLiUtilPool::getInstance( 'localhost', 'root', 'kirisame', 'mysql' );
print_r( $db1 );
$q = new MySQLiQueryUtil( 'localhost', 'root', 'kirisame', 'wechatclasstmp' );
$r = $q->table( 't_advice' )->where( 'id', '>', 2 )->where( function( $query ) { 
    $query->where( 'id', '>', 3 )->where( 'id', '<', 11 );
} )->get()->fir();
print_r( $r );

}

catch( \Exception $ex )
{
    print_r( $ex );
}



<?php

require( 'autoload.php' );

$config = require( 'config_beta.php' );

require( $baseDir . '/vendor/autoload.php' );

use EasyWeChat\Foundation\Application;
use EasyWeChat\Message\Text;

use Util\MySQLi\MySQLiUtil;
use Util\MySQLi\MySQLiUtilPool;
use Util\MySQLi\MySQLiQueryUtil;

try
{

$db1 = MySQLiUtilPool::getInstance( 'localhost', 'root', 'kirisame', 'mysql' );
print_r( $db1 );
$q = new MySQLiQueryUtil( 'localhost', 'root', 'kirisame', 'wechatclasstmp' );
$r = $q->table( 't_advice' )->where( 'id', '>', 2 )->where( function( $query ) { 
    $query->where( 'id', '>', 3 )->where( 'id', '<', 11 );
} )->get()->fir();
print_r( $r );

$app = new Application( $config );
print_r( $app );

}

catch( \Exception $ex )
{
    print_r( $ex );
}



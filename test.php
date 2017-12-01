<?php

require( 'autoload.php' );
/*
$config = require( 'config_beta.php' );
require( $baseDir . '/vendor/autoload.php' );
use EasyWeChat\Foundation\Application;
use EasyWeChat\Message\Text;
*/
use Util\ErrorUtil;


try
{
ErrorUtil::setGlobalExceptionHandler();
throw new Exception( '' );


/*
$logger = LogUtil::create( 'FS', __DIR__, '1.txt' );

print_r( $logger );
LogUtil::addInstance( 'EX', $logger );
$l = LogUtil::getInstance( 'E' );
print_r( $l );
LogUtil::_debug( 'adas' );
*/


/*
$db1 = MySQLiUtilPool::getInstance( 'localhost', 'root', 'kirisame', 'mysql' );
print_r( $db1 );
$q = new MySQLiQueryUtil( 'localhost', 'root', 'kirisame', 'wechatclasstmp' );
$r = $q->table( 't_advice' )->where( 'id', '>', 2 )->where( function( $query ) { 
    $query->where( 'id', '>', 3 )->where( 'id', '<', 11 );
} )->get()->fir();
print_r( $r );

$app = new Application( $config );
print_r( $app );
*/

}

catch( \Exception $ex )
{
    print_r( $ex );
}



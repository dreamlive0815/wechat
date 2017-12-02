<?php

require( 'autoload.php' );
/*
$config = require( 'config_beta.php' );
require( $baseDir . '/vendor/autoload.php' );
use EasyWeChat\Foundation\Application;
use EasyWeChat\Message\Text;
*/
use Util\Log\LogUtil;
use Util\ErrorUtil;
use Util\FilterUtil;
use Util\Exceptions\LogUtilException;
use Util\MySQLi\MySQLiUtilPool as DB;

$a = DB::getInstance( 'localhost', 'root', 'kirisame', 'wechatclasstmp' );
DB::addInstance( 'T', $a );

DB::setDefault( DB::getInstance( 'localhost', 'root', 'kirisame' ) );
$r = DB::exec( 'show databases' );
print_r( $r );
print_r( DB::$utils );
print_r( DB::getInstanceByKey( 'Tt' ) );
/*
LogUtil::addInstance( 'exceptionLogger', LogUtil::create( 'FS', __DIR__, 'ex.txt' ) );
LogUtil::addInstance( 'errorLogger', LogUtil::create( 'FS', __DIR__, 'err.txt' ) );
FilterUtil::addFilter( 'exceptionHandler', function( $info ) {
    $info['tolog'] = false;
    return $info;
}, 99 );
FilterUtil::addFilter( 'errorHandler', function( $info ) {
    $info['tolog'] = false;
    return $info;
}, 99 );
ErrorUtil::setGlobalExceptionHandler();
ErrorUtil::setGlobalErrorHandler();
$a = 1 / 0;
//throw new LogUtilException( '' );\
*/
try
{



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



<?php

require( 'MySQLiUtil.php' );
use Util\MySQLiUtil;


try
{

$db = new MySQLiUtil( 'localhost', 'root', 'kirisame' );
print_r( $db );

$r = $db->getTableList();
print_r( $r );
}

catch( \Exception $ex )
{
    print_r( $ex );
}



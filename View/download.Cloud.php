<?php

require( '../head.php' );


use Util\CommonUtil as CU;
use Util\HeaderUtil as HU;
use Util\CurlUtil as HTTP;
use Tool\Cloud;

$id = intval( CU::getR( 'id' ) );
$info = Cloud::getSongInfo( $id );
$url = $info['url'];
$name = $info['name'] . '.mp3';

$response = file_get_contents( $url );
/*
$max_time = ini_get( 'max_execution_time' );
$max_time = intval( $max_time );
--$max_time;
$max_time *= 1000;

$http = new HTTP( $url );
$response = $http->timeout( $max_time )->ipV4()->GET();
HU::length( $http->size() );
HU::filename( $name );
*/
header( 'Content-Disposition: attachment; filename=' . $name );
echo $response;

<?php

require( '../head.php' );


use Util\CommonUtil as CU;
use Util\HeaderUtil as HU;
use Util\CurlUtil as HTTP;
use Tool\CNKI;

$url = CU::getR( 'url' );
$url = base64_decode( $url );
$filename = CU::getR( 'filename' );
if( !$filename ) $filename = date( 'YmdHis' );
$isPDF = (boolean) preg_match( '/\.pdf/i', $filename );

$max_time = ini_get( 'max_execution_time' );
$max_time = intval( $max_time );
--$max_time;
$max_time *= 1000;

$args = [
    'url' => $url,
    'directdownload' => 'true',
];
$apiURL = CNKI::$apiURL;
//error_log( $url . "\n\n", 3, '../debug/download.log' );
$http = new HTTP( $apiURL );
$http->timeout( $max_time )->ipV4();
$response = $http->POST( $args ) ;
if( $isPDF ) HU::type( 'pdf' ); else HU::type( 'bin' );
HU::filename( $filename, !$isPDF );
HU::length( $http->size() );

echo $response;

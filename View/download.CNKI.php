<?php

require( '../head.php' );


use Util\CommonUtil as CU;
use Util\HeaderUtil as HU;
use Util\CurlUtil as HTTP;
use Tool\CNKI;

$docurl = CU::getR( 'docurl' );
$filename = CU::getR( 'filename' );
$isPDF = (boolean) preg_match( '/\.pdf/i', $filename );

$max_time = ini_get( 'max_execution_time' );
$max_time = intval( $max_time );
--$max_time;
$max_time *= 1000;

$http = new HTTP( CNKI::$apiURL );
$args = [
    'docurl' => $docurl,
    'filename' => $filename,
];
$response = $http->timeout( $max_time )->ipV4()->POST( $args );
if( $isPDF ) HU::type( 'pdf' );
HU::filename( $filename, !$isPDF );
HU::length( $http->size() );

echo $response;

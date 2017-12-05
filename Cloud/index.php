<?php

require( '../autoload.php' );

require( 'Cloud.php' );

$url = ' http://music.163.com/song/32408263/?userid=352148975';
$id = Cloud::getSongID( $url );
var_dump( $id );

/*
$id = isset( $_REQUEST['id'] ) ? $_REQUEST['id'] : null;

$info = [];
$detail = Cloud::getSongDetailInfo( $id );
$detail = $detail['songs'][0];
$info['name'] = $detail['name'];
$url = Cloud::getSongURLInfo( $id );
$url = $url['data'][0];
$info['url'] = $url['url'];

print_r( $info );
*/
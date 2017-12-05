<?php

require( 'head.php' );

$info = [];
$detail = Cloud::getSongDetailInfo( $id );
$detail = $detail['songs'][0];
$info['name'] = $detail['name'];
$url = Cloud::getSongURLInfo( $id );
$url = $url['data'][0];
$info['url'] = $url['url'];

print_r( $info );

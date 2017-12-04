<?php

require( '../autoload.php' );

require( 'Cloud.php' );

//$info = Cloud::getSongDetailInfo( '409916250' );
$info = Cloud::getSongURLInfo( '409916250' );
print_r( $info );
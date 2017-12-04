<?php

require( '../autoload.php' );

require( 'Cloud.php' );

$info = Cloud::getSongInfo( '496869422' );
print_r( $info );
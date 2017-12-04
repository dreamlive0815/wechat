<?php

require( '../autoload.php' );

require( 'Cloud.php' );

//echo Cloud::getRandString( 16 );
echo Cloud::encryptSongInfo( '518686034' );
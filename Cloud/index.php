<?php

require( '../autoload.php' );

require( 'Cloud.php' );

//echo Cloud::getRandString( 16 );
echo Cloud::AESEncrypt( '{"ids":"[484730184]","br":128000,"csrf_token":""}', '0CoJUm6Qyw8W8jud', '0102030405060708' );
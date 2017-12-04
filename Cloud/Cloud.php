<?php

use Util\CurlUtil;

class Cloud
{
    static function getRandString( $length )
    {
        $length = abs( intval( $length) );
        $pattern = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $lengthOfPattern = strlen( $pattern );
        $s = '';
        for( $i = 0; $i < $length; ++$i )
        {
            $index = mt_rand( 0, $lengthOfPattern - 1 );
            $s .= $pattern[$index];
        }
        return $s;
    }

    static function AESEncrypt( $data, $key, $iv )
    {
        $encrypted = mcrypt_encrypt( MCRYPT_RIJNDAEL_128, $key, $data, MCRYPT_MODE_CBC, $iv );
    
        $encrypted = base64_encode( $encrypted );
        return $encrypted;
    }
    
}
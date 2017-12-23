<?php


require( './head.php' );

use Tool\Google;
use Util\FileSystem\DirUtil as DIR;

$text = '你是不是想打架';

$dir = new DIR( __DIR__ . '/debug' );
$filename = sprintf( '%s%s.mp3', date( 'YmdHis' ), mt_rand( 0, 1000 ) );


$voice = Google::getTranslatorVoiceByText( $text );
$dir->createFile( $filename, $voice );
?>
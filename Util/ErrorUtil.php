<?php

namespace Util;

use Util\FilterUtil;
use Util\Log\LogUtil;

class ErrorUtil
{
    static $oldExceptionHandler;

    static function setGlobalExceptionHandler()
    {
        FilterUtil::addFilter( 'exceptionHandler', function( $info ) {
            print_r( $info );
        }, 999 );

        self::$oldExceptionHandler = set_exception_handler( [ self::class, 'exceptionHandler' ] );
    }

    static function exceptionHandler( $exception )
    {
        $info = [
            'exception' => $exception,
            'class' => get_class( $exception ),
            'tolog' => true,
        ];
        FilterUtil::applyFilter( 'exceptionHandler', $info );
    }

    static function setGlobalErrorHandler()
    {

    }
}
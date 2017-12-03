<?php

namespace Util;

use Util\FilterUtil;
use Util\Log\LogUtil;

class ErrorUtil
{
    static $oldExceptionHandler;
    static $oldErrorHandler;

    static function setGlobalExceptionHandler()
    {
        FilterUtil::addFilter( 'exceptionHandler', function( $info ) {
            if( !$info['tolog'] ) return;
            $exceptionLogger = LogUtil::getInstance( 'exceptionLogger' );
            if( $exceptionLogger ) $exceptionLogger->log( $info['exception']->__toString() );
            return $info;
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
        FilterUtil::addFilter( 'errorHandler', function( $info ) {
            if( !$info['tolog'] ) return;
            $errorLogger = LogUtil::getInstance( 'errorLogger' );
            if( $errorLogger ) $errorLogger->log( implode( ',', $info['error'] ) );
            return $info;
        }, 999 );

        self::$oldErrorHandler = set_error_handler( [ self::class, 'errorHandler' ], E_ALL | E_STRICT );
    }

    static function errorHandler( $errorNo, $errorMsg, $file, $line )
    {
        $info = [
            'error' => [
                'errorNo' => $errorNo,
                'errorMsg' => $errorMsg,
                'file' => $file,
                'line' => $line,
            ],
            'tolog' => true,
        ];
        FilterUtil::applyFilter( 'errorHandler', $info );
    }
}
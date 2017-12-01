<?php

namespace Util\Exceptions;

class LogUtilException extends \Exception
{
	function __construct( $errorMsg, $errorCode = 10001 )
    {
        parent::__construct( $errorMsg, $errorCode );
    }
}
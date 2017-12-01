<?php

namespace Util\Exceptions;

class LogUtilException extends \Exception
{
	function __construct( $errorMsg, $errorCode = 10002 )
    {
        parent::__construct( $errorMsg, $errorCode );
    }
}
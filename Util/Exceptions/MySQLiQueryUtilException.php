<?php

namespace Util\Exceptions;

class MySQLiQueryUtilException extends \Exception
{
	function __construct( $errorMsg, $errorCode = 10001 )
    {
        parent::__construct( $errorMsg, $errorCode );
    }
}
<?php

namespace Util\Exceptions;

class MySQLiUtilException extends \Exception
{
	function __construct( $errorMsg, $errorCode = 10002 )
    {
        parent::__construct( $errorMsg, $errorCode );
    }
}
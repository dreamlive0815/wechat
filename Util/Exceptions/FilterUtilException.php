<?php

namespace Util\Exceptions;

class FilterUtilException extends \Exception
{
	function __construct( $errorMsg, $errorCode = 10001 )
    {
        parent::__construct( $errorMsg, $errorCode );
    }
}
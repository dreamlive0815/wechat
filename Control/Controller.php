<?php

namespace Control;

use Util\JsonUtil as JSON;

class Controller
{
    static function echo( $errorCode = 0, $errorMsg = '', $data = null )
    {
        echo JSON::stringify( [
            'errorCode' => $errorCode,
            'errorMsg' => $errorMsg,
            'data' => $data,
        ] );
    }
}
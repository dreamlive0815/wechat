<?php

namespace Util;

class FormatUtil
{
    static function getMySQLDatetime( $timestamp = null )
    {
        $timestamp = intval( $timestamp );
        if( !$timestamp ) $timestamp = time();
        return date( 'Y-m-d H:i:s', $timestamp );
    }
}
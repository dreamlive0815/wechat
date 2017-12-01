<?php

namespace Util;

use Util\Exceptions\FilterUtilException;

class FilterUtil
{
    static $filters = [];
    static $count = 0;
    static $sorted = [];

    static function addFilter( $key, callable $filter, $priority )
    {
        $filters = &self::$filters;
        $key = strval( $key ); $priority = intval( $priority );
        if( !isset( $filters[$key] ) ) $filters[$key] = [];
        $filters[$key][] = [ 'filter' => $filter, 'priority' => $priority, 'id' => ++self::$count ];
        self::$sorted[$key] = false;
    }

    static function applyFilter( $key )
    {
        $filters = &self::$filters;
        $key = strval( $key );
        if( !isset( $filters[$key] ) ) throw new FilterUtilException( '过滤器不存在' );
        if( empty( self::$sorted[$key] ) )
        {
            usort( $filters[$key], function( $f1, $f2 ) {
                if( $f1['priority'] != $f2['priority'] ) return $f1['priority'] > $f2['priority'];
                return $f1['id'] > $f2['id'];
            } );
            self::$sorted[$key] = true;
        }

        $args = func_get_args();
        array_shift( $args );
        $val = $args ? $args[0] : null;
        foreach( $filters[$key] as $filter )
        {
            $realFilter = $filter['filter'];
            $val = call_user_func_array( $realFilter, $args );
            if( $args ) $args[0] = $val;
        }
        return $val;
    }

    static function hasFilter( $key )
    {
        $key = strval( $key );
        return isset( self::$filters[$key] );
    }

    static function resetFilter( $key )
    {
        $key = strval( $key );
        if( isset( self::$filters[$key] ) )
        {
            unset( self::$filters[$key] );
        }
    }
}
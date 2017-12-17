<?php

namespace Config;

class Config
{
    public $config;

    static function get( $type )
    {
        $class = "\\Config\\$type";
        return new $class();
    }

    function __construct( array $config = [] )
    {
        $this->config = array_merge( $this->config, $config );
    }

    function __get( $key )
    {
        return isset( $this->config[$key] ) ? $this->config[$key] : null;
    }

    function __set( $key, $val )
    {
        $this->config[$key] = $val;
    }

    function toArray()
    {
        return $this->config;
    }
}
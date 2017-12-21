<?php

namespace Handler\Plugin;

use EasyWeChat\Message\News;

class Plugin
{
    public $handled = false;

    protected $items = [];

    function __get( $key )
    {
        return isset( $this->items[$key] ) ? $this->items[$key] : null;
    }

    function __set( $key, $val )
    {
        $this->items[$key] = $val;
    }

    function run( $text )
    {

    }

    function getNews( array $args = [] )
    {
        return new News( $args );
    }
}
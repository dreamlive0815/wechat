<?php

namespace Model;

class Model
{
    public $map = [];

    public function __get( $key )
    {
        var_dump( $key );
        return isset( $this->map[$key] ) ? $this->map[$key] : null;
    }

    public function __set( $key, $val )
    {
        $this->map[$key] = $val;
    }
}
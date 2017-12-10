<?php

namespace Model;

class Model
{
    public $map = [];

    public function __get( $key )
    {
        return isset( $this->map[$key] ) ? isset( $this->map[$key] ) : null;
    }

    public function __set( $key, $val )
    {
        $this->map[$key] = $val;
    }
}
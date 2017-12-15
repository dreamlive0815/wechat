<?php

namespace Handler\Query;

class Course extends Query
{
    public $today = false;

    function buildArgs()
    {
        $args = parent::buildArgs();
        $args['passwd'] = $this->user->edu_passwd;
        return $args;
    }

    function renderData( $data )
    {
        //print_r( $data );
        return 'hhh';
    }
}